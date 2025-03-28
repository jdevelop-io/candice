<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\Event\EnrollmentApprovedEvent;
use Candice\Onboarding\Domain\Event\EnrollmentSubmittedEvent;
use Candice\Onboarding\Domain\Exception\EnrollmentAlreadyProcessedException;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
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

    public function approve(): void
    {
        if ($this->status !== EnrollmentStatus::PENDING_APPROVAL) {
            throw new EnrollmentAlreadyProcessedException($this->id->unwrap(), $this->status->unwrap());
        }

        $this->status = EnrollmentStatus::APPROVED;

        $this->record(new EnrollmentApprovedEvent($this->id));
    }
}
