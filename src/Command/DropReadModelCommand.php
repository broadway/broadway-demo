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

namespace BroadwayDemo\Command;

use BroadwayDemo\ReadModel\DBALRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * drops the read model table.
 */
class DropReadModelCommand extends Command
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DBALRepository
     */
    private $repository;

    public function __construct(Connection $connection, DBALRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
        $this->connection = $connection;
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('broadway:read-model:drop')
            ->setDescription('Drops the read model table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaManager = $this->connection->createSchemaManager();
        $table = $this->repository->configureTable(new Schema());

        if ($schemaManager->tablesExist([$table->getName()])) {
            $schemaManager->dropTable($table->getName());
            $output->writeln('<info>Dropped Broadway read model schema</info>');
        } else {
            $output->writeln('<info>Broadway read model schema does not exist</info>');
        }

        return 0;
    }
}
