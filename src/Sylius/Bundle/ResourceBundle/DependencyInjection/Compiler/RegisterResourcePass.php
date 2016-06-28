<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
final class RegisterResourcePass implements CompilerPassInterface
{
    /**
     * @var MetadataInterface
     */
    private $resourceMetadata;

    /**
     * @param MetadataInterface $resourceMetadata
     */
    public function __construct(MetadataInterface $resourceMetadata)
    {
        $this->resourceMetadata = $resourceMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        try {
            $container->findDefinition('sylius.resource_registry')->addMethodCall('add', [$this->resourceMetadata]);
        } catch (InvalidArgumentException $exception) {
            return;
        }
    }
}
