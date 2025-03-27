<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

interface EventBusInterface
{
    /**
     * @param iterable<DomainEvent> $events
     */
    public function publish(iterable $events): void;
}
