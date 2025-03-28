<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Event;

use Candice\Organization\Domain\ValueObject\OrganizationName;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class OrganizationRegisteredEvent extends DomainEvent
{
    public function __construct(
        private readonly RegistrationNumber $registrationNumber,
        private readonly OrganizationName $name
    ) {
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->registrationNumber;
    }

    public function getName(): OrganizationName
    {
        return $this->name;
    }
}
