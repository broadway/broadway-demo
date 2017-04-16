<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemo\ReadModel;

use BroadwayDemo\Basket\BasketWasCheckedOut;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\Repository;

class PeopleThatBoughtThisProductAlsoBoughtProjector extends Projector
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    protected function applyBasketWasCheckedOut(BasketWasCheckedOut $event)
    {
        foreach ($event->getProducts() as $productId => $count) {
            $readModel = $this->getReadModel($productId);

            $products = $event->getProducts();
            unset($products[$productId]);

            $this->addProducts($readModel, $products);

            $this->repository->save($readModel);
        }
    }

    private function getReadModel($productId)
    {
        $readModel = $this->repository->find($productId);

        if (null === $readModel) {
            $readModel = new PeopleThatBoughtThisProductAlsoBought($productId);
        }

        return $readModel;
    }

    private function addProducts(PeopleThatBoughtThisProductAlsoBought $readModel, array $products)
    {
        foreach ($products as $productId => $count) {
            $readModel->addProduct($productId, $count);
        }
    }
}
