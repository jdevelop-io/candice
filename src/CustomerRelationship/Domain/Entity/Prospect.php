<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Entity;

use Candice\CustomerRelationship\Domain\ValueObject\RegistrationNumber;

final class Prospect
{
    public function __construct(
        private readonly Organization $organization,
        private readonly RegistrationNumber $registrationNumber,
        private readonly string $name,
        private ?string $id = null
    ) {
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
