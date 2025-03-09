<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\Organization;
use Candice\Onboarding\Domain\Repository\OrganizationRepositoryInterface;

final class InMemoryOrganizationRepository implements OrganizationRepositoryInterface
{
    private array $organizationByRegistrationNumber = [];

    public function existsByRegistrationNumber(string $registrationNumber): bool
    {
        return isset($this->organizationByRegistrationNumber[$registrationNumber]);
    }

    public function save(Organization $organization): void
    {
        $this->organizationByRegistrationNumber[$organization->getRegistrationNumber()] = $organization;
    }
}
