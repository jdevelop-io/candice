<?php

declare(strict_types=1);

namespace Candice\Organization\Infrastructure\Repository;

use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;

final class InMemoryOrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * @var array<string, Organization>
     */
    private array $organizationByRegistrationNumber = [];

    public function insert(Organization $organization): void
    {
        $this->organizationByRegistrationNumber[$organization->getRegistrationNumber()->getValue()] = $organization;
    }

    public function existsByRegistrationNumber(RegistrationNumber $registrationNumber): bool
    {
        return isset($this->organizationByRegistrationNumber[$registrationNumber->getValue()]);
    }

    public function findByRegistrationNumber(RegistrationNumber $registrationNumber): ?Organization
    {
        return $this->organizationByRegistrationNumber[$registrationNumber->getValue()] ?? null;
    }
}
