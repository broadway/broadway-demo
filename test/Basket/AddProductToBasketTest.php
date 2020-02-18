<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace BroadwayDemo\Basket;

class AddProductToBasketTest extends BasketCommandHandlerTest
{
    /**
     * @test
     */
    public function it_adds_a_product_to_a_basket()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId = '1337';
        $productName = 'Awesome Product';
        $this->scenario
            ->withAggregateId((string) $basketId)
            ->given(array(new BasketWasPickedUp($basketId)))
            ->when(new AddProductToBasket($basketId, $productId, $productName))
            ->then(array(
                new ProductWasAddedToBasket($basketId, $productId, $productName),
        ));
    }

    /**
     * @test
     */
    public function multiple_products_can_be_added_to_a_basket()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId = '1337';
        $productName = 'Awesome Product';
        $this->scenario
            ->withAggregateId((string) $basketId)
            ->given(array(
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, 'otherId', $productName),
            ))
            ->when(new AddProductToBasket($basketId, $productId, $productName))
            ->then(array(
                new ProductWasAddedToBasket($basketId, $productId, $productName),
        ));
    }

    /**
     * @test
     */
    public function a_product_can_be_added_to_a_basket_multiple_times()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId = '1337';
        $productName = 'Awesome Product';
        $this->scenario
            ->withAggregateId((string) $basketId)
            ->given(array(
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, $productId, $productName),
            ))
            ->when(new AddProductToBasket($basketId, $productId, $productName))
            ->then(array(
                new ProductWasAddedToBasket($basketId, $productId, $productName),
        ));
    }
}
