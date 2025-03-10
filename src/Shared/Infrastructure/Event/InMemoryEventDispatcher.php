<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Event;

use Candice\Shared\Domain\Event\EventDispatcherInterface;
use Candice\Shared\Domain\Event\EventInterface;

final class InMemoryEventDispatcher implements EventDispatcherInterface
{
    /**
     * @var EventInterface[]
     */
    private array $events = [];

    public function dispatch(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    public function find(string $class): ?EventInterface
    {
        return array_find($this->events, fn($event) => $event instanceof $class);
    }
}
