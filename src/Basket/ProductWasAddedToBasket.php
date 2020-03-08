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

namespace BroadwayDemo\Basket;

class ProductWasAddedToBasket extends BasketEvent
{
    private $productId;
    private $productName;

    public function __construct(BasketId $basketId, string $productId, string $productName)
    {
        parent::__construct($basketId);

        $this->productId = $productId;
        $this->productName = $productName;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'productId' => $this->productId,
            'productName' => $this->productName,
        ]);
    }
}
