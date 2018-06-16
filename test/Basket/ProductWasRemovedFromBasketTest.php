<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace BroadwayDemo\Basket;

use Broadway\Serializer\Testing\SerializableEventTestCase;

class ProductWasRemovedFromBasketTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getters_of_event_work()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId = '1337';

        $event = new ProductWasRemovedFromBasket($basketId, $productId);

        $this->assertEquals($basketId, $event->getBasketId());
        $this->assertEquals($productId, $event->getProductId());
    }

    /**
     * {@inheritdoc}
     */
    protected function createEvent()
    {
        return new ProductWasRemovedFromBasket(
            new BasketId('00000000-0000-0000-0000-000000000000'),
            '1337'
        );
    }
}
