<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Event;

use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\Event\EventPublisherInterface;
use Countable;

final class InMemoryEventPublisher implements EventPublisherInterface, Countable
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    /**
     * @param iterable<DomainEvent> $events
     */
    public function publish(iterable $events): void
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    public function count(): int
    {
        return count($this->events);
    }
}
