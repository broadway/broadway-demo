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

class AddProductToBasket extends BasketCommand
{
    private $productId;
    private $productName;

    public function __construct(BasketId $basketId, string $productId, string $productName)
    {
        parent::__construct($basketId);

        $this->productId = $productId;
        $this->productName = $productName;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }
}
