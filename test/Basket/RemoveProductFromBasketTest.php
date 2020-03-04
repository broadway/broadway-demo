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

class RemoveProductFromBasketTest extends BasketCommandHandlerTest
{
    /**
     * @test
     */
    public function it_removes_a_product_that_was_added()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId = '1337';
        $this->scenario
            ->withAggregateId((string) $basketId)
            ->given([
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, $productId, 'Awesome'),
            ])
            ->when(new RemoveProductFromBasket($basketId, $productId))
            ->then([
                new ProductWasRemovedFromBasket($basketId, $productId),
        ]);
    }

    /**
     * @test
     */
    public function it_does_nothing_when_removing_a_product_that_is_not_in_a_basket()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId = '1337';
        $this->scenario
            ->withAggregateId((string) $basketId)
            ->given([
                new BasketWasPickedUp($basketId),
            ])
            ->when(new RemoveProductFromBasket($basketId, $productId))
            ->then([]);
    }

    /**
     * @test
     */
    public function it_only_removes_one_instance_of_a_product()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId = '1337';
        $this->scenario
            ->withAggregateId((string) $basketId)
            ->given([
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, $productId, 'Awesome'),
                new ProductWasAddedToBasket($basketId, $productId, 'Awesome'),
                new ProductWasRemovedFromBasket($basketId, $productId),
            ])
            ->when(new RemoveProductFromBasket($basketId, $productId))
            ->then([
                new ProductWasRemovedFromBasket($basketId, $productId),
        ]);
    }
}
