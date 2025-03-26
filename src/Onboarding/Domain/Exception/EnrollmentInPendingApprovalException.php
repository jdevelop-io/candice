<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class EnrollmentInPendingApprovalException extends DomainException
{
    public function __construct()
    {
        parent::__construct('An enrollment is already in progress for this applicant');
    }
}
