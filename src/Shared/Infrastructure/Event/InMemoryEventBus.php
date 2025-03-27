<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Event;

use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\Event\EventBusInterface;
use Candice\Shared\Domain\Event\EventDispatcherInterface;
use Candice\Shared\Domain\Event\EventStoreInterface;

final readonly class InMemoryEventBus implements EventBusInterface
{
    public function __construct(
        private EventStoreInterface $eventStore,
        private EventDispatcherInterface $eventPublisher
    ) {
    }

    /**
     * @param iterable<DomainEvent> $events
     */
    public function publish(iterable $events): void
    {
        $this->eventStore->append($events);
        $this->eventPublisher->dispatch($events);
    }
}
