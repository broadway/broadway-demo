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

use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Basket extends EventSourcedAggregateRoot
{
    private $basketId;
    private $productCountById = array();
    private $hasBeenCheckedOut = false;

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return $this->basketId;
    }

    public static function pickUpBasket(BasketId $basketId)
    {
        $basket = new Basket();
        $basket->pickUp($basketId);

        return $basket;
    }

    private function pickUp(BasketId $basketId)
    {
        $this->apply(
            new BasketWasPickedUp($basketId)
        );
    }

    protected function applyBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->basketId = $event->getBasketId();
    }

    public function addProduct($productId, $productName)
    {
        $this->apply(
            new ProductWasAddedToBasket(
                $this->basketId,
                $productId,
                $productName
            )
        );
    }

    protected function applyProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        $productId = $event->getProductId();

        if (! $this->productIsInBasket($productId)) {
            $this->productCountById[$productId] = 0;
        }

        $this->productCountById[$productId]++;
    }

    public function removeProduct($productId)
    {
        if (! $this->productIsInBasket($productId)) {
            return;
        }

        $this->apply(
            new ProductWasRemovedFromBasket(
                $this->basketId,
                $productId
            )
        );
    }

    protected function applyProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        $productId = $event->getProductId();

        if ($this->productIsInBasket($productId)) {
            $this->productCountById[$productId]--;

            if ($this->productCountById[$productId] === 0) {
                unset($this->productCountById[$productId]);
            }
        }
    }

    private function productIsInBasket($productId)
    {
        return isset($this->productCountById[$productId]) && $this->productCountById[$productId] > 0;
    }

    public function checkout()
    {
        if (count($this->productCountById) === 0) {
            throw new EmptyBasketException('Cannot checkout an empty basket');
        }

        if ($this->hasBeenCheckedOut) {
            return;
        }

        $this->apply(
            new BasketWasCheckedOut($this->basketId, $this->productCountById)
        );
    }

    protected function applyBasketWasCheckedOut(BasketWasCheckedOut $event)
    {
        $this->hasBeenCheckedOut = true;
    }
}
