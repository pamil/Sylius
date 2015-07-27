<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Core\Model;

use PhpSpec\ObjectBehavior;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class CountrySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\Core\Model\Country');
    }
    
    function it_implements_Sylius_country_interface()
    {
        $this->shouldImplement('Sylius\Component\Core\Model\CountryInterface');
    }

    function it_is_based_on_country_from_Addressing_component()
    {
        $this->shouldHaveType('Sylius\Component\Addressing\Model\CountryInterface');
        $this->shouldImplement('Sylius\Component\Addressing\Model\CountryInterface');
    }

    function it_is_enabled_by_default()
    {
        $this->isEnabled()->shouldReturn(true);
    }

    function it_can_be_either_enabled_or_disabled()
    {
        $this->setEnabled(false);
        $this->isEnabled()->shouldReturn(false);

        $this->setEnabled(true);
        $this->isEnabled()->shouldReturn(true);
    }
}