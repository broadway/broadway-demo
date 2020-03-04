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

use Broadway\Serializer\Serializable;

abstract class BasketEvent implements Serializable
{
    private $basketId;

    public function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    public function getBasketId(): BasketId
    {
        return $this->basketId;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): array
    {
        return ['basketId' => (string) $this->basketId];
    }
}
