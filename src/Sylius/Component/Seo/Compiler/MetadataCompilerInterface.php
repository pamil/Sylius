<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Seo\Compiler;

use Sylius\Component\Seo\Model\MetadataInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
interface MetadataCompilerInterface
{
    /**
     * @param MetadataInterface $metadata
     * @param MetadataInterface[] $parents
     *
     * @return MetadataInterface
     */
    public function compile(MetadataInterface $metadata, array $parents = []);
}