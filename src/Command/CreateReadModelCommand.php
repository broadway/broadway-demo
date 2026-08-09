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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates the read model table.
 */
class CreateReadModelCommand extends Command
{
    /**
     * @var DBALRepository
     */
    private $repository;

    /**
     * @var Connection
     */
    private $connection;

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
            ->setName('broadway:read-model:create')
            ->setDescription('Creates the read model table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($table = $this->repository->configureSchema($schemaManager->introspectSchema())) {
            $schemaManager->createTable($table);
            $output->writeln('<info>Created Broadway read model schema</info>');
        } else {
            $output->writeln('<info>Broadway read model schema already exists</info>');
        }

        return 0;
    }
}
