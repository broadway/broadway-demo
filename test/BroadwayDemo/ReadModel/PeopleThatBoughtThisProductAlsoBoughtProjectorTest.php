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

use BroadwayDemo\Basket\BasketCheckedOut;
use BroadwayDemo\Basket\BasketId;
use Broadway\ReadModel\InMemory\InMemoryRepository;
use Broadway\ReadModel\Testing\ProjectorScenarioTestCase;

class PeopleThatBoughtThisProductAlsoBoughtProjectorTest extends ProjectorScenarioTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function createProjector(InMemoryRepository $repository)
    {
        return new PeopleThatBoughtThisProductAlsoBoughtProjector($repository);
    }

    /**
     * @test
     */
    public function it_creates_a_read_model_on_checkout()
    {
        $basketId   = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId1 = 'productId1';
        $productId2 = 'productId2';
        $productId3 = 'productId3';

        $this->scenario->given(array())
            ->when(new BasketCheckedOut($basketId, array($productId1 => 1, $productId2 => 2, $productId3 => 3)))
            ->then(array(
                $this->createReadModel($productId1, array($productId2 => 2, $productId3 => 3)),
                $this->createReadModel($productId2, array($productId1 => 1, $productId3 => 3)),
                $this->createReadModel($productId3, array($productId1 => 1, $productId2 => 2)),
            ));
    }

    /**
     * @test
     */
    public function it_adds_the_product_count_for_items_that_were_purchased_earlier()
    {
        $basketId   = new BasketId('00000000-0000-0000-0000-000000000000');
        $productId1 = 'productId1';
        $productId2 = 'productId2';

        $this->scenario
            ->given(array(
                new BasketCheckedOut($basketId, array($productId1 => 1, $productId2 => 2))
            ))
            ->when(new BasketCheckedOut($basketId, array($productId1 => 1, $productId2 => 2)))
            ->then(array(
                $this->createReadModel($productId1, array($productId2 => 4)),
                $this->createReadModel($productId2, array($productId1 => 2)),
            ));
    }

    private function createReadModel($productId, array $products)
    {
        $readModel = new PeopleThatBoughtThisProductAlsoBought($productId);

        foreach ($products as $productId => $count)
        {
            $readModel->addProduct($productId, $count);
        }

        return $readModel;
    }
}
