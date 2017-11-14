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

use Assert\Assertion;
use Broadway\ReadModel\Identifiable;
use Broadway\ReadModel\Repository;
use Broadway\Serializer\Serializer;
use Doctrine\DBAL\Connection;

class DBALRepository implements Repository
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var string
     */
    private $tableName;
    /**
     * @var
     */
    private $class;

    public function __construct(
        Connection $connection,
        Serializer $serializer,
        $tableName,
        $class
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->tableName  = $tableName;
        $this->class      = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Identifiable $readModel)
    {
        Assertion::isInstanceOf($readModel, $this->class);

        $this->connection->insert($this->tableName, [
            'uuid' => $readModel->getId(),
            'data' => json_encode($this->serializer->serialize($readModel)),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        $row = $this->connection->fetchAssoc(sprintf('SELECT * FROM %s WHERE uuid = ?', $this->tableName), [$id]);
        if (false === $row) {
            return null;
        }

        return $this->serializer->deserialize(json_decode($row['data'], true));
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $fields)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    /**
     * {@inheritdoc}
     */
    public function remove($id)
    {
        // TODO: Implement delete() method.
    }
}
