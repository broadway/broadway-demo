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

class PeopleThatBoughtThisProductAlsoBought implements SerializableReadModel
{
    private $purchasedProductId;
    private $otherProducts = array();

    /**
     * @param string $purchasedProductId
     */
    public function __construct($purchasedProductId)
    {
        $this->purchasedProductId = $purchasedProductId;
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->purchasedProductId;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return array(
            'purchasedProductId' => $this->purchasedProductId,
            'otherProducts'      => $this->otherProducts,
        );
    }

    /**
     * @param string $productId
     * @param string $count
     */
    public function addProduct($productId, $count)
    {
        if (! isset($this->otherProducts[$productId])) {
            $this->otherProducts[$productId] = 0;
        }

        $this->otherProducts[$productId] += $count;
    }

    /**
     * {@inheritDoc}
     */
    public static function deserialize(array $data)
    {
        $readModel = new self($data['purchasedProductId']);

        $readModel->otherProducts = $data['otherProducts'];

        return $readModel;
    }

    /**
     * @param string $productId
     *
     * @return integer
     */
    public function numberOfTimesProductHasBeenPurchased($productId)
    {
        return $this->otherProducts[$productId];
    }
}
