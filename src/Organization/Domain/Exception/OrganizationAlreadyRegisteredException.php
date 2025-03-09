<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Exception;

use Exception;

final class OrganizationAlreadyRegisteredException extends Exception
{
    public function __construct(string $registrationNumber)
    {
        parent::__construct(
            sprintf('Organization with registration number <%s> already registered', $registrationNumber)
        );
    }
}
