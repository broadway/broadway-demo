<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemoBundle\Controller;

use Assert\Assertion as Assert;
use Broadway\CommandHandling\CommandBusInterface;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use BroadwayDemo\Basket\AddProductToBasket;
use BroadwayDemo\Basket\BasketId;
use BroadwayDemo\Basket\CheckoutBasket;
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
        CommandBusInterface $commandBus,
        UuidGeneratorInterface $uuidGenerator
    ) {
        $this->commandBus    = $commandBus;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @return JsonResponse
     */
    public function pickUpBasketAction(Request $request)
    {
        $basketId = new BasketId($this->uuidGenerator->generate());
        $command  = new PickUpBasket($basketId);

        $this->commandBus->dispatch($command);

        return new JsonResponse(array('id' => (string) $basketId));
    }

    /**
     * @param string $basketId
     *
     * @return Response
     */
    public function addProductToBasketAction(Request $request, $basketId)
    {
        $basketId = new BasketId($basketId);

        $productToAdd = $request->request->get('productId');
        $productName  = $request->request->get('productName');

        Assert::notNull($productName);

        $command = new AddProductToBasket($basketId, $productToAdd, $productName);

        $this->commandBus->dispatch($command);

        return new Response();
    }

    /**
     * @param string $basketId
     *
     * @return Response
     */
    public function removeProductFromBasketAction(Request $request, $basketId)
    {
        $basketId = new BasketId($basketId);

        $productToRemove = $request->request->get('productId');

        Assert::uuid($productToRemove);

        $command = new RemoveProductFromBasket($basketId, $productToRemove);

        $this->commandBus->dispatch($command);

        return new Response();
    }

    /**
     * @param string $basketId
     *
     * @return Response
     */
    public function checkoutAction(Request $request, $basketId)
    {
        $basketId = new BasketId($basketId);
        $command  = new CheckoutBasket($basketId);

        $this->commandBus->dispatch($command);

        return new Response();
    }
}
