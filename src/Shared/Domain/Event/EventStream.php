<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

final class EventStream
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    /**
     * @param DomainEvent[] $events
     */
    public function __construct(iterable $events)
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    /**
     * @param callable(DomainEvent): bool $callback
     */
    public function find(callable $callback): ?DomainEvent
    {
        return array_find($this->events, $callback);
    }
}
