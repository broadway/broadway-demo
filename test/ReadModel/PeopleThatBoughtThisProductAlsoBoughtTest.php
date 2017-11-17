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

use Broadway\ReadModel\SerializableReadModel;
use Broadway\ReadModel\Testing\SerializableReadModelTestCase;

class PeopleThatBoughtThisProductAlsoBoughtTest extends SerializableReadModelTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function createSerializableReadModel(): SerializableReadModel
    {
        $readModel = new PeopleThatBoughtThisProductAlsoBought('purchased_product_id');

        $readModel->addProduct('also_bought_product_id', 5);
        $readModel->addProduct('another_bought_product_id', 2);

        return $readModel;
    }

    /**
     * @test
     */
    public function it_exposes_the_bought_product_count()
    {
        $readModel = $this->createSerializableReadModel();

        $this->assertEquals(5, $readModel->numberOfTimesProductHasBeenPurchased('also_bought_product_id'));
        $this->assertEquals(2, $readModel->numberOfTimesProductHasBeenPurchased('another_bought_product_id'));
    }
}
