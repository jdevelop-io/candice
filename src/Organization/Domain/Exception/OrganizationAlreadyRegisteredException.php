<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Exception;

use Candice\Organization\Domain\ValueObject\RegistrationNumber;
use DomainException;

final class OrganizationAlreadyRegisteredException extends DomainException
{
    public function __construct(RegistrationNumber $registrationNumber)
    {
        parent::__construct(
            "Organization with registration number {$registrationNumber->getType()} {$registrationNumber->getValue()} is already registered."
        );
    }
}
