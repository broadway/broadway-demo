<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemo\Basket;

class PickUpBasketTest extends BasketCommandHandlerTest
{
    /**
     * @test
     */
    public function it_picks_up_a_basket()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $this->scenario
            ->given(array())
            ->when(new PickUpBasket($basketId))
            ->then(array(
                new BasketWasPickedUp($basketId)
        ));
    }
}
