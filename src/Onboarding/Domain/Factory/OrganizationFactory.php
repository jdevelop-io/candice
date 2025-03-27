<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Factory;

use Candice\Onboarding\Domain\Entity\Organization;
use Candice\Onboarding\Domain\ValueObject\OrganizationName;

final readonly class OrganizationFactory
{
    public function __construct(private RegistrationNumberFactory $registrationNumberFactory)
    {
    }

    public function create(
        string $registrationNumberType,
        string $registrationNumber,
        string $name
    ): Organization {
        return new Organization(
            $this->registrationNumberFactory->create($registrationNumberType, $registrationNumber),
            new OrganizationName($name),
        );
    }
}
