<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use DomainException;

final class EnrollmentNotFoundException extends DomainException
{
    public function __construct(EnrollmentId $enrollmentId)
    {
        parent::__construct("Enrollment with id $enrollmentId not found");
    }
}
