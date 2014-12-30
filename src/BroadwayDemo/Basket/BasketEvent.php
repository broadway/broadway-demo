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

use Broadway\Serializer\SerializableInterface;

abstract class BasketEvent implements SerializableInterface
{
    private $basketId;

    public function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    /**
     * @return BasketId
     */
    public function getBasketId()
    {
        return $this->basketId;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return array('basketId' => (string) $this->basketId);
    }
}
