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

use Assert\Assertion;
use Broadway\ReadModel\Identifiable;
use Broadway\ReadModel\Repository;
use Broadway\Serializer\Serializer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

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
     * @var string
     */
    private $class;

    public function __construct(
        Connection $connection,
        Serializer $serializer,
        string $tableName,
        string $class
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->tableName = $tableName;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Identifiable $readModel): void
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
    public function find($id): ?Identifiable
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
    public function findBy(array $fields): array
    {
        if (empty($fields)) {
            return [];
        }

        return array_values(array_filter($this->findAll(), function (Identifiable $readModel) use ($fields) {
            return $fields === array_intersect_assoc($this->serializer->serialize($readModel)['payload'], $fields);
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $rows = $this->connection->fetchAll(sprintf('SELECT * FROM %s', $this->tableName));

        return array_map(function (array $row) {
            return $this->serializer->deserialize(json_decode($row['data'], true));
        }, $rows);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($id): void
    {
        $this->connection->executeUpdate(sprintf('DELETE FROM %s WHERE uuid = ?', $this->tableName), [$id]);
    }

    /**
     * @return \Doctrine\DBAL\Schema\Table|null
     */
    public function configureSchema(Schema $schema)
    {
        if ($schema->hasTable($this->tableName)) {
            return null;
        }

        return $this->configureTable($schema);
    }

    public function configureTable(Schema $schema): Table
    {
        $table = $schema->createTable($this->tableName);
        $table->addColumn('uuid', 'guid', ['length' => 36]);
        $table->addColumn('data', 'text');
        $table->setPrimaryKey(['uuid']);

        return $table;
    }
}
