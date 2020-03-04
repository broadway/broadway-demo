<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) 2020 Broadway project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
        $event = new BasketWasPickedUp($basketId);

        $this->assertEquals($basketId, $event->getBasketId());
    }

    /**
     * {@inheritdoc}
     */
    protected function createEvent()
    {
        return new BasketWasPickedUp(new BasketId('00000000-0000-0000-0000-000000000000'));
    }
}
