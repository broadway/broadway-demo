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

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Drops the event store schema.
 */
class DropEventStoreCommand extends Command
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DBALEventStore
     */
    private $eventStore;

    public function __construct(Connection $connection, DBALEventStore $eventStore)
    {
        $this->connection = $connection;
        $this->eventStore = $eventStore;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('broadway:event-store:drop')
            ->setDescription('Drops the event store schema')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> command drops the schema in the default
connections database:

<info>php app/console %command.name%</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaManager = $this->connection->createSchemaManager();
        $table = $this->eventStore->configureTable(new Schema());

        if ($schemaManager->tablesExist([$table->getName()])) {
            $schemaManager->dropTable($table->getName());
            $output->writeln('<info>Dropped Broadway event-store schema</info>');
        } else {
            $output->writeln('<info>Broadway event-store schema does not exist</info>');
        }

        return 0;
    }
}
