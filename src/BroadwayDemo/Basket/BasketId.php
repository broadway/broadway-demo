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
use BroadwayDemo\Identifier;

final class BasketId implements Identifier
{
    private $basketId;

    /**
     * @param string $basketId
     */
    public function __construct($basketId)
    {
        Assert::string($basketId);
        Assert::uuid($basketId);

        $this->basketId = $basketId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->basketId;
    }
}
