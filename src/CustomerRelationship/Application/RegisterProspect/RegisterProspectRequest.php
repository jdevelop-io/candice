<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Application\RegisterProspect;

final readonly class RegisterProspectRequest
{
    public function __construct(
        private string $organizationId,
        private string $registrationNumber,
        private string $name
    ) {
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
