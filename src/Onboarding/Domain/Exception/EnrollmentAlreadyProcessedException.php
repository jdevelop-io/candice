<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use DomainException;

final class EnrollmentAlreadyProcessedException extends DomainException
{
    public function __construct(EnrollmentId $enrollmentId, EnrollmentStatus $enrollmentStatus)
    {
        parent::__construct("Enrollment $enrollmentId is already processed. Current status is {$enrollmentStatus->unwrap()}");
    }
}
