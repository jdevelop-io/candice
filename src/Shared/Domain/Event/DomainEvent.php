<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

use Candice\Shared\Domain\ValueObject\AggregateRootId;

abstract class DomainEvent
{
    abstract public function getAggregateRootId(): AggregateRootId;
}
