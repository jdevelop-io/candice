<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Entity;

use Candice\Executive\Domain\Event\ExecutiveRegisteredEvent;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use Candice\Executive\Domain\ValueObject\ExecutiveFullName;
use Candice\Shared\Domain\Event\DomainEventPublisherTrait;

final class Executive
{
    use DomainEventPublisherTrait;

    public function __construct(
        private readonly ExecutiveEmail $email,
        private readonly ExecutiveFullName $fullName,
        private readonly Organization $organization
    ) {
    }

    public static function register(
        Organization $organization,
        ExecutiveEmail $email,
        ExecutiveFullName $fullName
    ): self {
        $executive = new self($email, $fullName, $organization);

        $executive->record(new ExecutiveRegisteredEvent($email, $fullName, $organization));

        return $executive;
    }

    public function getEmail(): ExecutiveEmail
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
