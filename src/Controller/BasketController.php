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

namespace BroadwayDemo\Controller;

use Assert\Assertion as Assert;
use Broadway\CommandHandling\CommandBus;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use BroadwayDemo\Basket\AddProductToBasket;
use BroadwayDemo\Basket\BasketId;
use BroadwayDemo\Basket\Checkout;
use BroadwayDemo\Basket\PickUpBasket;
use BroadwayDemo\Basket\RemoveProductFromBasket;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BasketController
{
    private $commandBus;
    private $uuidGenerator;

    public function __construct(
        CommandBus $commandBus,
        UuidGeneratorInterface $uuidGenerator
    ) {
        $this->commandBus = $commandBus;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function pickUpBasketAction(): JsonResponse
    {
        $basketId = new BasketId($this->uuidGenerator->generate());
        $command = new PickUpBasket($basketId);

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => (string) $basketId]);
    }

    public function addProductToBasketAction(Request $request, string $basketId): Response
    {
        $basketId = new BasketId($basketId);

        $productToAdd = $request->request->get('productId');
        $productName = $request->request->get('productName');

        Assert::notNull($productName);

        $command = new AddProductToBasket($basketId, $productToAdd, $productName);

        $this->commandBus->dispatch($command);

        return new Response();
    }

    public function removeProductFromBasketAction(Request $request, string $basketId): Response
    {
        $basketId = new BasketId($basketId);

        $productToRemove = $request->request->get('productId');

        $command = new RemoveProductFromBasket($basketId, $productToRemove);

        $this->commandBus->dispatch($command);

        return new Response();
    }

    public function checkoutAction(string $basketId): Response
    {
        $basketId = new BasketId($basketId);
        $command = new Checkout($basketId);

        $this->commandBus->dispatch($command);

        return new Response();
    }
}
