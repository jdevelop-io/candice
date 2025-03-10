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

    public function existsByRegistrationNumber(RegistrationNumber $registrationNumber): bool
    {
        return isset($this->organizationByRegistrationNumber[$registrationNumber->unwrap()]);
    }

    public function findByRegistrationNumber(RegistrationNumber $registrationNumber): ?Organization
    {
        return $this->organizationByRegistrationNumber[$registrationNumber->unwrap()] ?? null;
    }


    public function save(Organization $organization): void
    {
        $this->organizationByRegistrationNumber[$organization->getRegistrationNumber()->unwrap()] = $organization;
    }
}
