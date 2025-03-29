<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\Event\EnrollmentApprovedEvent;
use Candice\Onboarding\Domain\Event\EnrollmentRejectedEvent;
use Candice\Onboarding\Domain\Event\EnrollmentSubmittedEvent;
use Candice\Onboarding\Domain\Exception\EnrollmentAlreadyProcessedException;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentProcessingDateTime;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Shared\Domain\Event\DomainEventPublisherTrait;

final class Enrollment
{
    use DomainEventPublisherTrait;

    public function __construct(
        private readonly EnrollmentId $id,
        private readonly Applicant $applicant,
        private readonly Organization $organization,
        private EnrollmentStatus $status,
        private ?Administrator $processedBy = null,
        private ?EnrollmentProcessingDateTime $processedAt = null,
    ) {
    }

    public static function submit(EnrollmentId $id, Applicant $applicant, Organization $organization): self
    {
        $enrollment = new self(
            $id,
            $applicant,
            $organization,
            EnrollmentStatus::PENDING_APPROVAL
        );

        $enrollment->record(new EnrollmentSubmittedEvent($enrollment->getId()));

        return $enrollment;
    }

    public function getId(): EnrollmentId
    {
        return $this->id;
    }

    public function getApplicant(): Applicant
    {
        return $this->applicant;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getStatus(): EnrollmentStatus
    {
        return $this->status;
    }

    public function getProcessedBy(): ?Administrator
    {
        return $this->processedBy;
    }

    public function getProcessedAt(): ?EnrollmentProcessingDateTime
    {
        return $this->processedAt;
    }

    public function approve(Administrator $administrator, EnrollmentProcessingDateTime $approvedAt): void
    {
        if ($this->status !== EnrollmentStatus::PENDING_APPROVAL) {
            throw new EnrollmentAlreadyProcessedException($this->id, $this->status);
        }

        $this->status = EnrollmentStatus::APPROVED;
        $this->processedBy = $administrator;
        $this->processedAt = $approvedAt;

        $this->record(
            new EnrollmentApprovedEvent(
                $this->id, $this->applicant, $this->organization, $administrator,
                $approvedAt
            )
        );
    }

    public function reject(Administrator $administrator, EnrollmentProcessingDateTime $rejectedAt): void
    {
        if ($this->status !== EnrollmentStatus::PENDING_APPROVAL) {
            throw new EnrollmentAlreadyProcessedException($this->id, $this->status);
        }

        $this->status = EnrollmentStatus::REJECTED;
        $this->processedBy = $administrator;
        $this->processedAt = $rejectedAt;

        $this->record(new EnrollmentRejectedEvent($this->id));
    }
}
