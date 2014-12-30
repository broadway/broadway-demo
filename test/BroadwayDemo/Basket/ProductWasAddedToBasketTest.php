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

use Broadway\Serializer\Testing\SerializableEventTestCase;

class ProductWasAddedToBasketTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getters_of_event_work()
    {
        $basketId    = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId   = '1337';
        $productName = 'Best Product Ever';

        $event = new ProductWasAddedToBasket($basketId, $productId, $productName);

        $this->assertEquals($basketId, $event->getBasketId());
        $this->assertEquals($productId, $event->getProductId());
        $this->assertEquals($productName, $event->getProductName());
    }

    /**
     * {@inheritDoc}
     */
    protected function createEvent()
    {
        return new ProductWasAddedToBasket(
            new BasketId('00000000-0000-0000-0000-000000000000'),
            '1337',
            'Best Product Ever'
        );
    }
}
