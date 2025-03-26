<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;

final readonly class Enrollment
{
    public function __construct(
        private EnrollmentId $id,
        private RegistrationNumber $registrationNumber,
        private Applicant $applicant,
        private EnrollmentStatus $status,
    ) {
    }

    public static function submit(EnrollmentId $id, RegistrationNumber $registrationNumber, Applicant $applicant): self
    {
        return new self($id, $registrationNumber, $applicant, EnrollmentStatus::PENDING_APPROVAL);
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
