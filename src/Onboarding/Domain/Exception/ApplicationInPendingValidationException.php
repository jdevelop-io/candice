<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Exception;

final class ApplicationInPendingValidationException extends Exception
{
    public function __construct(string $organizationRegistrationNumber)
    {
        parent::__construct(
            sprintf(
                "Application for organization with registration number <%s> is in pending validation",
                $organizationRegistrationNumber
            )
        );
    }
}
