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
 * Resolves given target entities with container parameters.
 * Usable only with *doctrine/orm* driver.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
final class DoctrineTargetEntityResolverPass implements CompilerPassInterface
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
            $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        } catch (InvalidArgumentException $exception) {
            return;
        }

        if (!$this->resourceMetadata->hasClass('interface')) {
            return;
        }

        $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [
            $this->resourceMetadata->getClass('interface'),
            $this->resourceMetadata->getClass('model'),
            [],
        ]);

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_listener')) {
            $resolveTargetEntityListener->addTag('doctrine.event_listener', ['event' => 'loadClassMetadata']);
        }
    }
}
