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

namespace BroadwayDemo\Functional;

use BroadwayDemo\WebTestCase;

/**
 * @group functional
 */
class AddProductToBasketTest extends WebTestCase
{
    /**
     * @test
     * @doesNotPerformAssertion
     */
    public function it_adds_a_product_to_the_basket()
    {
        $result = $this->post('/basket', array());

        $basketId = $result['id'];

        $this->post('/basket/'.$basketId.'/addProduct', array('productId' => '4d4ceed3-0aed-46f4-a86f-ca78ba5ea5db', 'productName' => 'ThisProductRocks'));
    }

    /**
     * @test
     * @doesNotPerformAssertion
     */
    public function it_removes_a_product_from_the_basket()
    {
        $result = $this->post('/basket', array());

        $basketId = $result['id'];

        $this->post('/basket/'.$basketId.'/addProduct', array('productId' => '4d4ceed3-0aed-46f4-a86f-ca78ba5ea5db', 'productName' => 'ThisProductRocks'));
        $this->post('/basket/'.$basketId.'/removeProduct', array('productId' => '4d4ceed3-0aed-46f4-a86f-ca78ba5ea5db', 'productName' => 'ThisProductRocks'));
    }

    /**
     * @test
     */
    public function it_exposes_and_advice_after_checking_out_a_basket()
    {
        $result = $this->post('/basket', array());

        $basketId = $result['id'];

        $firstProductId = '4d4ceed3-0aed-46f4-a86f-ca78ba5ea5db';
        $secondProductId = '8e7af740-edca-4f2e-bdb8-07eadb7bc0fb';
        $thirdProductId = 'aaabf740-edca-4f2e-bdb8-07eadb7bc0fb';
        $this->post('/basket/'.$basketId.'/addProduct', array('productId' => $firstProductId, 'productName' => 'ThisProductRocks'));
        $this->post('/basket/'.$basketId.'/addProduct', array('productId' => $secondProductId, 'productName' => 'ThisProductRocks'));
        $this->post('/basket/'.$basketId.'/addProduct', array('productId' => $secondProductId, 'productName' => 'ThisProductRocks'));
        $this->post('/basket/'.$basketId.'/addProduct', array('productId' => $thirdProductId, 'productName' => 'ThisProductRocks'));

        $this->post('/basket/'.$basketId.'/checkout', array());

        $result = $this->get('/advice/'.$firstProductId);
        $this->assertEquals($firstProductId, $result['purchasedProductId']);
        $this->assertCount(2, $result['otherProducts']);
        $this->assertEquals(2, $result['otherProducts'][$secondProductId]);
        $this->assertEquals(1, $result['otherProducts'][$thirdProductId]);
    }
}
