<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Domain\Event;

use Candice\Contexts\Shared\Domain\ValueObject\AggregateRootId;

interface EventStoreInterface
{
    /**
     * @param iterable<DomainEvent> $events
     */
    public function append(iterable $events): void;

    /**
     * @return iterable<DomainEvent>
     */
    public function load(AggregateRootId $aggregateRootId): iterable;
}
