<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Extension;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DriverProvider;
use Sylius\Component\Resource\Metadata\Metadata;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
abstract class AbstractResourceExtension extends Extension
{
    /**
     * @param ContainerBuilder $container
     * @param string $applicationName
     * @param string $driverName
     * @param array $resources
     */
    final protected function loadResources(ContainerBuilder $container, $applicationName, $driverName, array $resources)
    {
        $container->setParameter(sprintf('%s.driver.%s', $this->getAlias(), $driverName), true);
        $container->setParameter(sprintf('%s.driver', $this->getAlias()), $driverName);

        foreach ($resources as $resourceName => $resourceConfiguration) {
            $metadata = Metadata::fromAliasAndConfiguration(
                sprintf('%s.%s', $applicationName, $resourceName),
                array_merge(['driver' => $driverName], $resourceConfiguration)
            );

            DriverProvider::get($metadata)->load($container, $metadata);

            if ($metadata->hasParameter('translation')) {
                $metadata = Metadata::fromAliasAndConfiguration(
                    sprintf('%s.%s_translation', $applicationName, $resourceName),
                    array_merge(['driver' => $driverName], $resourceConfiguration['translation'])
                );

                DriverProvider::get($metadata)->load($container, $metadata);
            }
        }
    }
}
