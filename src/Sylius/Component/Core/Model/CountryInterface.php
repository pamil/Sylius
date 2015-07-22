<?php

namespace Sylius\Component\Core\Model;

use Sylius\Component\Addressing\Model\CountryInterface as BaseCountryInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
interface CountryInterface extends BaseCountryInterface
{
    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return boolean
     */
    public function isEnabled();
}