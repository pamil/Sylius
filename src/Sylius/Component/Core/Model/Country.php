<?php

namespace Sylius\Component\Core\Model;

use Sylius\Component\Addressing\Model\Country as BaseCountry;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class Country extends BaseCountry implements CountryInterface
{
    /**
     * @var boolean
     */
    protected $enabled = true;

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}