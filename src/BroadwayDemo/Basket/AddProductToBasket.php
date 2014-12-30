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

class AddProductToBasket extends BasketCommand
{
    /**
     * @param string $productId
     * @param string $productName
     */
    public function __construct(BasketId $basketId, $productId, $productName)
    {
        parent::__construct($basketId);

        $this->productId   = $productId;
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }
}
