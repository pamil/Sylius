<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DriverProvider;
use Sylius\Component\Resource\Metadata\Metadata;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Gonzalo Vilaseca <gvilaseca@reiss.co.uk>
 */
class SyliusResourceExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration($config, $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $configFiles = [
            'services.xml',
            'controller.xml',
            'storage.xml',
            'routing.xml',
            'twig.xml',
        ];

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }

        $bundles = $container->getParameter('kernel.bundles');
        if (array_key_exists('SyliusGridBundle', $bundles)) {
            $loader->load('grid.xml');
        }

        if ($config['translation']['enabled']) {
            $loader->load('translation.xml');

            $container->setParameter('sylius.translation.default_locale', $config['translation']['default_locale']);
            $container->setAlias('sylius.translation.locale_provider', $config['translation']['locale_provider']);
            $container->setAlias('sylius.translation.available_locales_provider', $config['translation']['available_locales_provider']);
            $container->setParameter('sylius.translation.available_locales', $config['translation']['available_locales']);
        }

        $container->setParameter('sylius.resource.settings', $config['settings']);
        $container->setAlias('sylius.resource_controller.authorization_checker', $config['authorization_checker']);

        $this->loadPersistence($config['drivers'], $config['resources'], $loader);
        $this->loadResources($config['resources'], $container);
    }

    /**
     * @param array $enabledDrivers
     * @param array $resources
     * @param LoaderInterface $loader
     */
    private function loadPersistence(array $enabledDrivers, array $resources, LoaderInterface $loader)
    {
        foreach ($resources as $alias => $resource) {
            if (!in_array($resource['driver'], $enabledDrivers, true)) {
                throw new InvalidArgumentException(sprintf(
                    'Resource "%s" uses driver "%s", but this driver has not been enabled.',
                    $alias, $resource['driver']
                ));
            }
        }

        foreach ($enabledDrivers as $enabledDriver) {
            $loader->load(sprintf('driver/%s.xml', $enabledDriver));
        }
    }

    /**
     * @param array $resources
     * @param ContainerBuilder $container
     */
    private function loadResources(array $resources, ContainerBuilder $container)
    {
        foreach ($resources as $resourceName => $resourceConfiguration) {
            $metadata = Metadata::fromAliasAndConfiguration($resourceName, $resourceConfiguration);

            DriverProvider::get($metadata)->load($container, $metadata);

            if ($metadata->hasParameter('translation')) {
                $metadata = Metadata::fromAliasAndConfiguration(
                    sprintf('%s_translation', $resourceName),
                    array_merge(['driver' => $resourceConfiguration['driver']], $resourceConfiguration['translation'])
                );

                DriverProvider::get($metadata)->load($container, $metadata);
            }
        }
    }
}
