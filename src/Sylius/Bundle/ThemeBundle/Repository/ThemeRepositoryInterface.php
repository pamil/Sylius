<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ThemeBundle\Repository;

use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
interface ThemeRepositoryInterface
{
    /**
     * @return ThemeInterface[]
     */
    public function findAll();

    /**
     * @param string $logicalName
     *
     * @return ThemeInterface|null
     */
    public function findByLogicalName($logicalName);

    /**
     * @param string $path
     *
     * @return ThemeInterface|null
     */
    public function findByPath($path);
}