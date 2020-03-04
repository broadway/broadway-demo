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

use Broadway\ReadModel\SerializableReadModel;

class PeopleThatBoughtThisProductAlsoBought implements SerializableReadModel
{
    private $purchasedProductId;
    private $otherProducts = [];

    public function __construct(string $purchasedProductId)
    {
        $this->purchasedProductId = $purchasedProductId;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->purchasedProductId;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): array
    {
        return [
            'purchasedProductId' => $this->purchasedProductId,
            'otherProducts' => $this->otherProducts,
        ];
    }

    public function addProduct(string $productId, int $count)
    {
        if (!isset($this->otherProducts[$productId])) {
            $this->otherProducts[$productId] = 0;
        }

        $this->otherProducts[$productId] += $count;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $readModel = new self($data['purchasedProductId']);

        $readModel->otherProducts = $data['otherProducts'];

        return $readModel;
    }

    public function numberOfTimesProductHasBeenPurchased(string $productId): int
    {
        return $this->otherProducts[$productId];
    }
}
