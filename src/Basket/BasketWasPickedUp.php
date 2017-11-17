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

class BasketWasPickedUp extends BasketEvent
{
    /**
     * {@inheritDoc}
     */
    public static function deserialize(array $data)
    {
        return new self(new BasketId($data['basketId']));
    }
}
