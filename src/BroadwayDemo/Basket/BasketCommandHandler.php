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

use Broadway\CommandHandling\CommandHandler;

class BasketCommandHandler extends CommandHandler
{
    private $repository;

    public function __construct(BasketRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function handlePickUpBasket(PickUpBasket $command)
    {
        $basket = Basket::pickUpBasket($command->getBasketId());

        $this->repository->add($basket);
    }

    protected function handleAddProductToBasket(AddProductToBasket $command)
    {
        $basket = $this->repository->load($command->getBasketId());
        $basket->addProduct($command->getProductId(), $command->getProductName());

        $this->repository->add($basket);
    }

    public function handleRemoveProductFromBasket(RemoveProductFromBasket $command)
    {
        $basket = $this->repository->load($command->getBasketId());
        $basket->removeProduct($command->getProductId());

        $this->repository->add($basket);
    }

    public function handleCheckout(Checkout $command)
    {
        $basket = $this->repository->load($command->getBasketId());
        $basket->checkout();

        $this->repository->add($basket);
    }
}
