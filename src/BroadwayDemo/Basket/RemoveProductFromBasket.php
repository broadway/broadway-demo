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

class RemoveProductFromBasket extends BasketCommand
{
    private $productId;

    /**
     * @param string $productId
     */
    public function __construct(BasketId $basketId, $productId)
    {
        parent::__construct($basketId);

        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }
}
