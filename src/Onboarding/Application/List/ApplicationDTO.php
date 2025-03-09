<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\List;

final readonly class ApplicationDTO
{
    public function __construct(
        private string $id,
        private string $userEmail,
        private string $organizationRegistrationNumber,
        private string $organizationName,
        private string $status
    ) {
    }

    public function getId(): string
    {
        return $this->id;
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

    public function getStatus(): string
    {
        return $this->status;
    }
}
