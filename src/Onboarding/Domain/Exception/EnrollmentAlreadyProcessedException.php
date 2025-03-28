<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class EnrollmentAlreadyProcessedException extends DomainException
{
    public function __construct(string $enrollmentId, string $enrollmentStatus)
    {
        parent::__construct("Enrollment $enrollmentId is already processed. Current status is $enrollmentStatus");
    }
}
