<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Event;

use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;
use Candice\IdentityAndAccess\Domain\ValueObject\UserFullName;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class UserRegisteredEvent extends DomainEvent
{
    public function __construct(private readonly UserEmail $userEmail, private readonly UserFullName $fullName)
    {
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->userEmail;
    }

    public function getFullName(): UserFullName
    {
        return $this->fullName;
    }
}
