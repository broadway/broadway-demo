<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace BroadwayDemo;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    const ENVIRONMENT = 'functional';

    protected $client;

    /**
     * {@inheritdoc}
     */
    protected static function createKernel(array $options = [])
    {
        return new AppKernel(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $container = $this->client->getContainer();

        $this->setUpEventStore($container);
        $this->setUpReadModel($container);
    }

    private function setUpEventStore($container)
    {
        $schemaManager = $container->get('doctrine.dbal.default_connection')->getSchemaManager();
        $schema = $schemaManager->createSchema();
        $eventStore = $container->get('broadway.event_store');

        if ($table = $eventStore->configureSchema($schema)) {
            $schemaManager->dropAndCreateTable($table);
        }
    }

    private function setUpReadModel($container)
    {
        $schemaManager = $container->get('doctrine.dbal.default_connection')->getSchemaManager();
        $schema = $schemaManager->createSchema();
        $readModelRepository = $container->get('broadway_demo.read_model.repository.people_that_bought_this_product');

        if ($table = $readModelRepository->configureSchema($schema)) {
            $schemaManager->dropAndCreateTable($table);
        }
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    protected function post($url, array $body)
    {
        $response = $this->getDynamicResponse($url, 'POST', $body);

        return json_decode($response->getContent(), true);
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    protected function put($url, array $body)
    {
        $response = $this->getDynamicResponse($url, 'PUT', $body);

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
     *
     * @return mixed
     */
    protected function get($url)
    {
        $response = $this->getDynamicResponse($url);

        return json_decode($response->getContent(), true);
    }

    /**
     * @param string $url
     * @param string method
     * @param array body the body to be sent during the request
     *
     * @return Response
     */
    protected function getDynamicResponse($url, $method = 'GET', array $body = [])
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
