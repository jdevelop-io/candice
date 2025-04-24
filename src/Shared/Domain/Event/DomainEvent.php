<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Domain\Event;

use Candice\Contexts\Shared\Domain\ValueObject\AggregateRootId;
use Candice\Contexts\Shared\Domain\ValueObject\OccurredOn;

abstract readonly class DomainEvent
{
    public function __construct(
        private AggregateRootId $aggregateRootId,
        private OccurredOn $occurredOn,
    ) {
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function getOccurredOn(): OccurredOn
    {
        return $this->occurredOn;
    }
}
