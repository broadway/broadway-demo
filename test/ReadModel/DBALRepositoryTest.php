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

use Broadway\ReadModel\Repository;
use Broadway\ReadModel\Testing\RepositoryTestCase;
use Broadway\ReadModel\Testing\RepositoryTestReadModel;
use Broadway\Serializer\SimpleInterfaceSerializer;
use Doctrine\DBAL\DriverManager;

class DBALRepositoryTest extends RepositoryTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createRepository(): Repository
    {
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);

        $repository = new DBALRepository(
            $connection,
            new SimpleInterfaceSerializer(),
            'read_model',
            RepositoryTestReadModel::class
        );

        $schemaManager    = $connection->getSchemaManager();
        $schema           = $schemaManager->createSchema();

        if ($table = $repository->configureSchema($schema)) {
            $schemaManager->createTable($table);
        }

        return $repository;
    }

    /**
     * @test
     */
    public function it_finds_by_one_element_in_array()
    {
        $this->markTestSkipped('not implemented');
    }
}
