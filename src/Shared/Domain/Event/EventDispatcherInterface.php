<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

interface EventDispatcherInterface
{
    /**
     * @param iterable<DomainEvent> $events
     */
    public function dispatch(iterable $events): void;
}
