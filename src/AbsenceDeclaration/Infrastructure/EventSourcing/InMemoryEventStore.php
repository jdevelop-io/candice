<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Infrastructure\EventSourcing;

use Candice\Contexts\Shared\Domain\Event\DomainEvent;
use Candice\Contexts\Shared\Domain\Event\EventBusInterface;
use Candice\Contexts\Shared\Domain\Event\EventStoreInterface;
use Candice\Contexts\Shared\Domain\ValueObject\AggregateRootId;
use Override;

final class InMemoryEventStore implements EventStoreInterface
{
    /**
     * @var array<string, DomainEvent[]>
     */
    private array $eventsByAggregateRootId = [];

    public function __construct(
        private EventBusInterface $eventBus,
    ) {
    }

    #[Override]
    public function append(iterable $events): void
    {
        foreach ($events as $event) {
            $this->eventsByAggregateRootId[$event->getAggregateRootId()->getValue()][] = $event;
            $this->eventBus->publish($event);
        }

        $this->eventBus->flush();
    }

    #[Override]
    public function load(AggregateRootId $aggregateRootId): iterable
    {
        return $this->eventsByAggregateRootId[$aggregateRootId->getValue()] ?? [];
    }
}
