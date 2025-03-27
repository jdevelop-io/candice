<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

interface EventPublisherInterface
{
    /**
     * @param iterable<DomainEvent> $events
     */
    public function publish(iterable $events): void;
}
