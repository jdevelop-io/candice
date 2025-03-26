<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class InvalidApplicantPositionException extends DomainException
{
    public function __construct(string $position)
    {
        parent::__construct("Invalid applicant position: $position");
    }
}
