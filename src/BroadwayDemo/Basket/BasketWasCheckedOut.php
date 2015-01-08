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

class BasketWasCheckedOut extends BasketEvent
{
    private $products;

    public function __construct(BasketId $basketId, array $products)
    {
        parent::__construct($basketId);

        $this->products = $products;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function serialize()
    {
        return array_merge(parent::serialize(), array(
            'products' => $this->products,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public static function deserialize(array $data)
    {
        return new self(
            new BasketId($data['basketId']),
            $data['products']
        );
    }
}
