<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Service;

use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;

final readonly class OrganizationRegistrationService
{
    public function __construct(private RegistrationNumberFactory $registrationNumberFactory)
    {
    }

    public function createRegistrationNumber(string $type, string $value): RegistrationNumber
    {
        return $this->registrationNumberFactory->create($type, $value);
    }
}
