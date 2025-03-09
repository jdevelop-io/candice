<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\Enum\ApplicationStatus;
use Candice\Onboarding\Domain\Exception\ApplicationNotPendingApprovalException;

final class Application
{
    public function __construct(
        private readonly string $userEmail,
        private readonly string $organizationRegistrationNumber,
        private readonly string $organizationName,
        private ApplicationStatus $status,
        private ?string $id = null
    ) {
    }

    public static function apply(
        string $userEmail,
        string $organizationRegistrationNumber,
        string $organizationName,
        ?string $id = null
    ) {
        return new self(
            $userEmail,
            $organizationRegistrationNumber,
            $organizationName,
            ApplicationStatus::PENDING_APPROVAL,
            $id
        );
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
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

    public function approve(): void
    {
        if ($this->status !== ApplicationStatus::PENDING_APPROVAL) {
            throw new ApplicationNotPendingApprovalException($this->status->value);
        }

        $this->status = ApplicationStatus::APPROVED;
    }
}
