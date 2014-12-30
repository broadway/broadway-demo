<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemo\Basket;

use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventStore\EventStoreInterface;
use Broadway\ReadModel\InMemory\InMemoryRepository;

abstract class BasketCommandHandlerTest extends CommandHandlerScenarioTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function createCommandHandler(EventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        return new BasketCommandHandler(
            new BasketRepository($eventStore, $eventBus)
        );
    }
}
