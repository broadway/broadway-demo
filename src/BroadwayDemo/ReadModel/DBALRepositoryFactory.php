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

use Broadway\ReadModel\RepositoryFactory;
use Broadway\Serializer\Serializer;
use Doctrine\DBAL\Connection;

class DBALRepositoryFactory implements RepositoryFactory
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
     * @var
     */
    private $tableName;

    public function __construct(
        Connection $connection,
        Serializer $serializer,
        $tableName
    ) {

        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->tableName = $tableName;
    }

    /**
     * {@inheritdoc}
     */
    public function create($name, $class)
    {
        return new DBALRepository($this->connection, $this->serializer, $this->tableName, $class);
    }
}
