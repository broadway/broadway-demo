<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemo;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    const ENVIRONMENT = 'functional';

    protected $client;

    /**
     * {@inheritDoc}
     */
    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = self::createClient();
        $container = $this->client->getContainer();

        $this->setUpEventStore($container);
        $this->setUpReadModel($container);
    }

    private function setUpEventStore($container)
    {
        $connection = $container->get('doctrine.dbal.default_connection');
        $eventStore = $container->get('broadway.event_store');

        $table = $eventStore->configureTable();

        $schemaManager = $connection->getSchemaManager();

        $schemaManager->dropAndCreateTable($table);
    }

    private function setUpReadModel($container)
    {
        $schemaManager = $container->get('doctrine.dbal.default_connection')->getSchemaManager();
        $schema        = $schemaManager->createSchema();

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

    /**
     * @return array
     */
    protected function getReadModelIndices()
    {
        return array(
            'broadway_demo.people_that_bought_this_product',
        );
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        return $this->client->getContainer();
    }

    /**
     * @param string $uuid
     *
     * @return \Broadway\Domain\DomainEventStream
     */
    protected function getEvents($uuid)
    {
        return $this->getEventStore()->load($uuid);
    }

    /**
     * @return \Broadway\EventStore\EventStore
     */
    protected function getEventStore()
    {
        return $this->getContainer()->get('broadway.event_store.dbal');
    }

    /**
     * @param string $url
     * @param array $body
     * @return mixed
     */
    protected function post($url, array $body)
    {
        $response = $this->getResponse($url, 'POST', $body);

        return json_decode($response->getContent(), true);
    }

    /**
     * @param string $url
     * @param array $body
     * @return mixed
     */
    protected function put($url, array $body)
    {
        $response = $this->getResponse($url, 'PUT', $body);

        return json_decode($response->getContent(), true);
    }

    /**
     * @param string $url
     */
    protected function delete($url)
    {
        $this->client->request(
            'DELETE',
            $url
        );
    }

    /**
     * @param string $url
     * @return mixed
     */
    protected function get($url)
    {
        $response = $this->getResponse($url);

        return json_decode($response->getContent(), true);
    }

    /**
     * @param string $url
     * @param string method
     * @param array body the body to be sent during the request
     *
     * @return Response
     */
    protected function getResponse($url, $method = 'GET', array $body = array())
    {
        $this->client->request(
            $method,
            $url,
            $body
        );

        // client->request returns a DomCrawler, which doesnt work for json
        return $this->client->getResponse();
    }
}
