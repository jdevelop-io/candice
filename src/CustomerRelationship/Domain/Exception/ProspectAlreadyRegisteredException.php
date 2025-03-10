<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Exception;

use Exception;

final class ProspectAlreadyRegisteredException extends Exception
{
    public function __construct(string $registrationNumber)
    {
        parent::__construct("Prospect with registration number <{$registrationNumber}> is already registered.");
    }
}
