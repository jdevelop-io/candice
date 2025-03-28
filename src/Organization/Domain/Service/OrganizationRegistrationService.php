<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Service;

use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\Factory\OrganizationFactory;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\ValueObject\OrganizationName;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;

final readonly class OrganizationRegistrationService
{
    public function __construct(
        private OrganizationFactory $organizationFactory,
        private RegistrationNumberFactory $registrationNumberFactory
    ) {
    }

    public function createRegistrationNumber(string $type, string $value): RegistrationNumber
    {
        return $this->registrationNumberFactory->create($type, $value);
    }

    public function register(
        string $registrationNumberType,
        string $registrationNumber,
        string $name
    ): Organization {
        $registrationNumber = $this->registrationNumberFactory->create($registrationNumberType, $registrationNumber);

        return $this->organizationFactory->register(
            $registrationNumber,
            new OrganizationName($name),
        );
    }
}
