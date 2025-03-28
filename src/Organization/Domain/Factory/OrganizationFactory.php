<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Factory;

use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\ValueObject\OrganizationName;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;

final readonly class OrganizationFactory
{
    public function register(RegistrationNumber $registrationNumber, OrganizationName $name): Organization
    {
        return Organization::register($registrationNumber, $name);
    }
}
