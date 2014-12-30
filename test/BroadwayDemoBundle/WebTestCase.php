<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemoBundle;

use Doctrine\DBAL\Schema\Schema;
use IC\Bundle\Base\TestBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    const ENVIRONMENT = 'functional';

    /**
     * {@inheritDoc}
     */
    protected static function createKernel(array $options = array())
    {
        require_once __DIR__ . '/../../app/AppKernel.php';

        return new \AppKernel(
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

        $container = $this->getClient()->getContainer();

        $this->setUpEventStore($container);
        $this->setUpReadModel($container);
        $this->setUpSagaState($container);
    }

    private function setUpEventStore($container)
    {
        $connection = $container->get('doctrine.dbal.default_connection');
        $eventStore = $container->get('broadway.event_store.dbal');

        $table = $eventStore->configureTable();

        $schemaManager = $connection->getSchemaManager();

        $schemaManager->dropAndCreateTable($table);
    }

    private function setUpSagaState($container)
    {
        $mongo = $container->get('broadway.saga.state.mongodb_connection');

        $mongo->dropDatabase('broadway_functional');
    }

    private function setUpReadModel($container)
    {
        $elasticsearch = $container->get('broadway.elasticsearch.client');

        $indices = $this->getReadModelIndices();

        foreach ($indices as $index) {
            if ($elasticsearch->indices()->exists(array('index' => $index))) {
                $elasticsearch->indices()->delete(array('index' => $index));
            }
        }
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
     * @return Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        return $this->getClient()->getContainer();
    }

    /**
     * @param string $uuid
     *
     * @return Broadway\Domain\DomainEventStreamInterface
     */
    protected function getEvents($uuid)
    {
        return $this->getEventStore()->load($uuid);
    }

    /**
     * @return Broadway\EventStore\EventStoreInterface
     */
    protected function getEventStore()
    {
        return $this->getContainer()->get('broadway.event_store.dbal');
    }

    protected function post($url, array $body)
    {
        $this->getClient()->request(
            'POST',
            $url,
            $body
        );

        $response = $this->getClient()->getResponse(); // client->request returns a DomCrawler, which doesnt work for json

        return json_decode($response->getContent(), true);
    }

    protected function put($url, array $body)
    {
        $this->getClient()->request(
            'PUT',
            $url,
            $body
        );

        $response = $this->getClient()->getResponse(); // client->request returns a DomCrawler, which doesnt work for json

        return json_decode($response->getContent(), true);
    }

    protected function delete($url)
    {
        $this->getClient()->request(
            'DELETE',
            $url
        );
    }

    protected function get($url)
    {
        $this->getClient()->request(
            'GET',
            $url
        );

        $response = $this->getClient()->getResponse(); // client->request returns a DomCrawler, which doesnt work for json

        return json_decode($response->getContent(), true);
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    protected function getResponse($url)
    {
        $this->getClient()->request(
            'GET',
            $url
        );

        return $this->getClient()->getResponse();
    }
}
