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

use Broadway\CommandHandling\SimpleCommandHandler;

class BasketCommandHandler extends SimpleCommandHandler
{
    private $repository;

    public function __construct(BasketRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handlePickUpBasket(PickUpBasket $command)
    {
        $basket = Basket::pickUpBasket($command->getBasketId());

        $this->repository->save($basket);
    }

    public function handleAddProductToBasket(AddProductToBasket $command)
    {
        $basket = $this->repository->load($command->getBasketId());
        $basket->addProduct($command->getProductId(), $command->getProductName());

        $this->repository->save($basket);
    }

    public function handleRemoveProductFromBasket(RemoveProductFromBasket $command)
    {
        $basket = $this->repository->load($command->getBasketId());
        $basket->removeProduct($command->getProductId());

        $this->repository->save($basket);
    }

    public function handleCheckout(Checkout $command)
    {
        $basket = $this->repository->load($command->getBasketId());
        $basket->checkout();

        $this->repository->save($basket);
    }
}
