<?php

/*
 * This file is part of the broadway/broadway package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates the read model table.
 */
class CreateReadModelCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('broadway:read-model:create')
            ->setDescription('Creates the read model table');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schemaManager = $this->getContainer()
            ->get('doctrine.dbal.default_connection')
            ->getSchemaManager();
        $schema = $schemaManager->createSchema();
        $tableName = 'read_model';

        if ($schema->hasTable($tableName)) {
            $schema->dropTable($tableName);
            $schemaManager->dropTable($tableName);
        }

        $table = $schema->createTable('read_model');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('uuid', 'guid', ['length' => 36]);
        $table->addColumn('data', 'text');
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid']);

        $schemaManager->createTable($table);
    }
}
