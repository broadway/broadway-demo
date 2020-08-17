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

namespace BroadwayDemo\ReadModel;

use Broadway\ReadModel\Projector;
use Broadway\ReadModel\Repository;
use BroadwayDemo\Basket\BasketCheckedOut;

class PeopleThatBoughtThisProductAlsoBoughtProjector extends Projector
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    protected function applyBasketCheckedOut(BasketCheckedOut $event)
    {
        foreach ($event->getProducts() as $productId => $count) {
            $readModel = $this->getReadModel($productId);

            $products = $event->getProducts();
            unset($products[$productId]);

            $this->addProducts($readModel, $products);

            $this->repository->save($readModel);
        }
    }

    private function getReadModel($productId): PeopleThatBoughtThisProductAlsoBought
    {
        $readModel = $this->repository->find($productId);

        if (null === $readModel) {
            $readModel = new PeopleThatBoughtThisProductAlsoBought((string) $productId);
        }

        return $readModel;
    }

    private function addProducts(PeopleThatBoughtThisProductAlsoBought $readModel, array $products)
    {
        foreach ($products as $productId => $count) {
            $readModel->addProduct((string) $productId, $count);
        }
    }
}
