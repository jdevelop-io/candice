<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\Enum\ApplicationStatus;

final class Application
{
    private ApplicationStatus $status;

    public function __construct(
        private readonly string $id,
        private readonly string $userEmail,
        private readonly string $organizationRegistrationNumber,
        private readonly string $organizationName
    ) {
        $this->status = ApplicationStatus::PENDING_APPROVAL;
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

    public function getStatus(): ApplicationStatus
    {
        return $this->status;
    }
}
