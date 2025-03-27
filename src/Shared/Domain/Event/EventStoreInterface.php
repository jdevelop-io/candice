<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

use Candice\Shared\Domain\ValueObject\AggregateRootId;

interface EventStoreInterface
{
    /**
     * @param iterable<DomainEvent> $events
     */
    public function append(iterable $events): void;

    public function load(AggregateRootId $aggregateRootId): EventStream;
}
