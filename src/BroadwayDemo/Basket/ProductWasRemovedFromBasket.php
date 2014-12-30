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

class ProductWasRemovedFromBasket extends BasketEvent
{
    private $productId;

    /**
     * @param string $productId
     */
    public function __construct(BasketId $basketId, $productId)
    {
        parent::__construct($basketId);

        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * {@inheritDoc}
     */
    public static function deserialize(array $data)
    {
        return new self(
            new BasketId($data['basketId']),
            $data['productId']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return array_merge(parent::serialize(), array(
            'productId' => $this->productId,
        ));
    }
}
