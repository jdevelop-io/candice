<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Entity;

use Candice\Organization\Domain\Event\OrganizationRegisteredEvent;
use Candice\Organization\Domain\ValueObject\OrganizationName;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;
use Candice\Shared\Domain\Event\DomainEventPublisherTrait;

final class Organization
{
    use DomainEventPublisherTrait;

    public function __construct(
        private readonly RegistrationNumber $registrationNumber,
        private readonly OrganizationName $name
    ) {
    }

    public static function register(RegistrationNumber $registrationNumber, OrganizationName $name): self
    {
        $organization = new self($registrationNumber, $name);

        $organization->record(new OrganizationRegisteredEvent($registrationNumber, $name));

        return $organization;
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getName(): OrganizationName
    {
        return $this->name;
    }
}
