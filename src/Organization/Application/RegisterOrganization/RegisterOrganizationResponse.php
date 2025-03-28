<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegisterOrganization;

final readonly class RegisterOrganizationResponse
{
    public function __construct(
        private string $organizationRegistrationNumberType,
        private string $organizationRegistrationNumber
    ) {
    }

    public function getOrganizationRegistrationNumberType(): string
    {
        return $this->organizationRegistrationNumberType;
    }

    public function getOrganizationRegistrationNumber(): string
    {
        return $this->organizationRegistrationNumber;
    }
}
