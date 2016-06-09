<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Fixture;

use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
final class SampleFixture implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('sample_fixture');

        $root
            ->children()
                ->arrayNode('administrators')
                    ->prototype('scalar')
        ;

        return $treeBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $options)
    {
        foreach ($options['administrators'] as $administrator) {
            printf('Creating an admin account for "%s"' . "\n", $administrator);
        }
    }
}
