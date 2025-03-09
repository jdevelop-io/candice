<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Service;

use Candice\Onboarding\Domain\Exception\InvalidOrganizationRegistrationNumberException;
use Candice\Onboarding\Domain\Service\OrganizationServiceInterface;

final class InMemoryOrganizationService implements OrganizationServiceInterface
{
    /**
     * @var string[]
     */
    private array $organizationsRegistrationNumbers = [];

    public function add(string $registrationNumber): void
    {
        $this->organizationsRegistrationNumbers[] = $registrationNumber;
    }

    /**
     * @throws InvalidOrganizationRegistrationNumberException
     */
    public function validateRegistrationNumber(string $registrationNumber): void
    {
        if (preg_match('/^\d{9}$/', $registrationNumber) !== 1) {
            throw new InvalidOrganizationRegistrationNumberException($registrationNumber);
        }
    }

    public function existsByRegistrationNumber(string $registrationNumber): bool
    {
        return in_array($registrationNumber, $this->organizationsRegistrationNumbers, true);
    }
}
