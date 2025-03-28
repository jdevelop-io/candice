<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Onboarding\Domain\ValueObject\OrganizationName;
use DomainException;

final class EnrollmentResubmissionException extends DomainException
{
    public function __construct(OrganizationName $organizationName, EnrollmentStatus $enrollmentStatus)
    {
        parent::__construct(
            match ($enrollmentStatus) {
                EnrollmentStatus::PENDING_APPROVAL => "An enrollment has already been submitted for $organizationName. Please wait for the approval process to complete.",
                EnrollmentStatus::APPROVED => "An enrollment has already been approved for $organizationName",
            }
        );
    }
}
