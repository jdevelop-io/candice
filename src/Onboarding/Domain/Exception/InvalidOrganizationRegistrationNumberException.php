<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Exception;

final class InvalidOrganizationRegistrationNumberException extends Exception
{
    public function __construct(string $registrationNumber, ?string $reason = null)
    {
        parent::__construct(
            sprintf('Invalid organization registration number: %s. Reason: %s', $registrationNumber, $reason)
        );
    }
}
