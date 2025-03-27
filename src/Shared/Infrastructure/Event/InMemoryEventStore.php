<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Event;

use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\Event\EventStoreInterface;
use Candice\Shared\Domain\Event\EventStream;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class InMemoryEventStore implements EventStoreInterface
{
    /**
     * @var array<string, DomainEvent[]>
     */
    private array $eventsByAggregateRootId = [];

    /**
     * @param iterable<DomainEvent> $events
     */
    public function append(iterable $events): void
    {
        foreach ($events as $event) {
            $aggregateRootId = $event->getAggregateRootId();

            $this->eventsByAggregateRootId[$aggregateRootId->unwrap()][] = $event;
        }
    }

    public function load(AggregateRootId $aggregateRootId): EventStream
    {
        return new EventStream($this->eventsByAggregateRootId[$aggregateRootId->unwrap()] ?? []);
    }
}
