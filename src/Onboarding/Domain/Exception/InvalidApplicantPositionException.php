<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class InvalidApplicantPositionException extends DomainException
{
    public function __construct(string $applicantPosition)
    {
        parent::__construct("Invalid applicant position: $applicantPosition");
    }
}
