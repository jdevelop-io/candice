<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\Event\EnrollmentSubmittedEvent;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;
use Candice\Shared\Domain\Event\DomainEventPublisherTrait;

final class Enrollment
{
    use DomainEventPublisherTrait;

    public function __construct(
        private readonly EnrollmentId $id,
        private readonly RegistrationNumber $registrationNumber,
        private readonly Applicant $applicant,
        private readonly EnrollmentStatus $status,
    ) {
    }

    public static function submit(EnrollmentId $id, RegistrationNumber $registrationNumber, Applicant $applicant): self
    {
        $enrollment = new self($id, $registrationNumber, $applicant, EnrollmentStatus::PENDING_APPROVAL);

        $enrollment->record(new EnrollmentSubmittedEvent($enrollment->getId()));

        return $enrollment;
    }

    public function getId(): EnrollmentId
    {
        return $this->id;
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getApplicant(): Applicant
    {
        return $this->applicant;
    }

    public function getStatus(): EnrollmentStatus
    {
        return $this->status;
    }
}
