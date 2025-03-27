<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\ValueObject\OrganizationName;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;

final readonly class Organization
{
    public function __construct(private RegistrationNumber $registrationNumber, private OrganizationName $name)
    {
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getName(): OrganizationName
    {
        return $this->name;
    }
}
