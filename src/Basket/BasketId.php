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

use Assert\Assertion as Assert;

final class BasketId
{
    private $basketId;

    public function __construct(string $basketId)
    {
        Assert::uuid($basketId);

        $this->basketId = $basketId;
    }

    public function __toString(): string
    {
        return $this->basketId;
    }
}
