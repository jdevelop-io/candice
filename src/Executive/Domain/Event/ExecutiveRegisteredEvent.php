<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Event;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use Candice\Executive\Domain\ValueObject\ExecutiveFullName;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class ExecutiveRegisteredEvent extends DomainEvent
{
    public function __construct(
        private readonly ExecutiveEmail $email,
        private readonly ExecutiveFullName $fullName,
        private readonly Organization $organization
    ) {
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->email;
    }

    public function getFullName(): ExecutiveFullName
    {
        return $this->fullName;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }
}
