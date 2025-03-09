<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Apply;

final readonly class ApplyRequest
{
    public function __construct(
        private string $userEmail,
        private string $organizationRegistrationNumber,
        private string $organizationName
    ) {
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getOrganizationRegistrationNumber(): string
    {
        return $this->organizationRegistrationNumber;
    }

    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }
}
