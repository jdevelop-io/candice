<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Exception;

use Exception;

final class OrganizationNotFoundException extends Exception
{
    public function __construct(string $registrationNumber)
    {
        parent::__construct(sprintf('Organization with registration number <%s> not found', $registrationNumber));
    }
}
