<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;

final readonly class Enrollment
{
    public function __construct(private RegistrationNumber $registrationNumber)
    {
    }

    public static function submit(RegistrationNumber $registrationNumber): self
    {
        return new self($registrationNumber);
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getStatus(): EnrollmentStatus
    {
        return EnrollmentStatus::PENDING_APPROVAL;
    }
}
