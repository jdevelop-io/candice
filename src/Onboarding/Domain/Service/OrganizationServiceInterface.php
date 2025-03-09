<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Service;

use Candice\Onboarding\Domain\Exception\InvalidOrganizationRegistrationNumberException;

interface OrganizationServiceInterface
{
    /**
     * @throws InvalidOrganizationRegistrationNumberException
     */
    public function validateRegistrationNumber(string $registrationNumber): void;

    public function existsByRegistrationNumber(string $registrationNumber): bool;
}
