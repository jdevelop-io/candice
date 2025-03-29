<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Symfony\Event;

use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\Event\EventDispatcherInterface;

final readonly class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(private \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * @param iterable<DomainEvent> $events
     */
    public function dispatch(iterable $events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
