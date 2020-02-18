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

namespace BroadwayDemo\Basket;

class ProductWasRemovedFromBasket extends BasketEvent
{
    private $productId;

    public function __construct(BasketId $basketId, string $productId)
    {
        parent::__construct($basketId);

        $this->productId = $productId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new self(
            new BasketId($data['basketId']),
            $data['productId']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): array
    {
        return array_merge(parent::serialize(), [
            'productId' => $this->productId,
        ]);
    }
}
