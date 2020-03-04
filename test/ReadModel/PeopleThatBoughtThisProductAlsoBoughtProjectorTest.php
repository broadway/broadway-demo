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

use Broadway\ReadModel\InMemory\InMemoryRepository;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\Testing\ProjectorScenarioTestCase;
use BroadwayDemo\Basket\BasketCheckedOut;
use BroadwayDemo\Basket\BasketId;

class PeopleThatBoughtThisProductAlsoBoughtProjectorTest extends ProjectorScenarioTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createProjector(InMemoryRepository $repository): Projector
    {
        return new PeopleThatBoughtThisProductAlsoBoughtProjector($repository);
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_checkout()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId1 = 'productId1';
        $productId2 = 'productId2';
        $productId3 = 'productId3';

        $this->scenario->given([])
            ->when(new BasketCheckedOut($basketId, [$productId1 => 1, $productId2 => 2, $productId3 => 3]))
            ->then([
                $this->createReadModel($productId1, [$productId2 => 2, $productId3 => 3]),
                $this->createReadModel($productId2, [$productId1 => 1, $productId3 => 3]),
                $this->createReadModel($productId3, [$productId1 => 1, $productId2 => 2]),
            ]);
    }

    /**
     * @test
     */
    public function it_adds_the_product_count_for_items_that_were_purchased_earlier()
    {
        $basketId = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId1 = 'productId1';
        $productId2 = 'productId2';

        $this->scenario
            ->given([
                new BasketCheckedOut($basketId, [$productId1 => 1, $productId2 => 2]),
            ])
            ->when(new BasketCheckedOut($basketId, [$productId1 => 1, $productId2 => 2]))
            ->then([
                $this->createReadModel($productId1, [$productId2 => 4]),
                $this->createReadModel($productId2, [$productId1 => 2]),
            ]);
    }

    private function createReadModel($productId, array $products)
    {
        $readModel = new PeopleThatBoughtThisProductAlsoBought($productId);

        foreach ($products as $productId => $count) {
            $readModel->addProduct($productId, $count);
        }

        return $readModel;
    }
}
