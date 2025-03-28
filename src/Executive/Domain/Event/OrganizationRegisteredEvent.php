<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Event;

use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Executive\Domain\ValueObject\OrganizationName;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class OrganizationRegisteredEvent extends DomainEvent
{
    public function __construct(private readonly OrganizationId $id, private readonly OrganizationName $name)
    {
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->id;
    }

    public function getName(): OrganizationName
    {
        return $this->name;
    }
}
