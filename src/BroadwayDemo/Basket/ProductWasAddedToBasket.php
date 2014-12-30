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

class ProductWasAddedToBasket extends BasketEvent
{
    private $productId;
    private $productName;

    /**
     * @param string $productId
     * @param string $productName
     */
    public function __construct(BasketId $basketId, $productId, $productName)
    {
        parent::__construct($basketId);

        $this->productId   = $productId;
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * {@inheritDoc}
     */
    public static function deserialize(array $data)
    {
        return new self(
            new BasketId($data['basketId']),
            $data['productId'],
            $data['productName']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return array_merge(parent::serialize(), array(
            'productId'   => $this->productId,
            'productName' => $this->productName,
        ));
    }
}
