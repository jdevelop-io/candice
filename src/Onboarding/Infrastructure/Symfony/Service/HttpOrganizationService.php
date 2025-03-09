<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Exception\InvalidOrganizationRegistrationNumberException;
use Candice\Onboarding\Domain\Service\OrganizationServiceInterface;

final readonly class HttpOrganizationService implements OrganizationServiceInterface
{
    /**
     * @throws InvalidOrganizationRegistrationNumberException
     */
    public function validateRegistrationNumber(string $registrationNumber): void
    {
    }

    public function existsByRegistrationNumber(string $registrationNumber): bool
    {
        return false;
    }
}
