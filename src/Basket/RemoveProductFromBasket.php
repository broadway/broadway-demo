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

class RemoveProductFromBasket extends BasketCommand
{
    private $productId;

    public function __construct(BasketId $basketId, string $productId)
    {
        parent::__construct($basketId);

        $this->productId = $productId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
}
