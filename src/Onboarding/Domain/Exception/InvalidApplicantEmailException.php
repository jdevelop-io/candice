<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class InvalidApplicantEmailException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Invalid applicant email: $email");
    }
}
