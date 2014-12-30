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

class BasketWasPickedUpTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getters_of_event_work()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $event    = new BasketWasPickedUp($basketId);

        $this->assertEquals($basketId, $event->getBasketId());
    }

    /**
     * {@inheritDoc}
     */
    protected function createEvent()
    {
        return new BasketWasPickedUp(new BasketId('00000000-0000-0000-0000-000000000000'));
    }
}
