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

class CheckoutBasketTest extends BasketCommandHandlerTest
{
    /**
     * @test
     */
    public function it_checks_out_a_basket()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $this->scenario
            ->withAggregateId($basketId)
            ->given(array(
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, 'productId', 'Awesome Product Name'),
                new ProductWasAddedToBasket($basketId, 'productId', 'Awesome Product Name'),
                new ProductWasAddedToBasket($basketId, 'productId2', 'Awesome Product Name')
            ))
            ->when(new CheckoutBasket($basketId))
            ->then(array(
                new BasketWasCheckedOut($basketId, array('productId' => 2, 'productId2' => 1))
        ));
    }

    /**
     * @test
     * @expectedException BroadwayDemo\Basket\EmptyBasketException
     */
    public function it_cannot_checkout_an_empty_basket()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $this->scenario
            ->withAggregateId($basketId)
            ->given(array(
                new BasketWasPickedUp($basketId),
            ))
            ->when(new CheckoutBasket($basketId));
    }

    /**
     * @test
     * @expectedException BroadwayDemo\Basket\EmptyBasketException
     */
    public function it_cannot_checkout_a_basket_that_has_been_emptied()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $this->scenario
            ->withAggregateId($basketId)
            ->given(array(
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, 'productId', 'Awesome Product Name'),
                new ProductWasRemovedFromBasket($basketId, 'productId')
            ))
            ->when(new CheckoutBasket($basketId));
    }

    /**
     * @test
     */
    public function nothing_happens_when_checking_out_a_basket_for_a_second_time()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $this->scenario
            ->withAggregateId($basketId)
            ->given(array(
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, 'productId', 'Awesome Product Name'),
                new BasketWasCheckedOut($basketId, array('productId' => 1))
            ))
            ->when(new CheckoutBasket($basketId))
            ->then(array());
    }
}
