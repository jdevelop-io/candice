<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Exception;

use Exception;

final class InvalidRegistrationNumberException extends Exception
{
    public function __construct(string $registrationNumber)
    {
        parent::__construct(sprintf('Registration number <%s> is invalid', $registrationNumber));
    }
}
