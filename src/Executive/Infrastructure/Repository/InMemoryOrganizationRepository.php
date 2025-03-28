<?php

declare(strict_types=1);

namespace Candice\Executive\Infrastructure\Repository;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Executive\Domain\ValueObject\OrganizationId;

final class InMemoryOrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * @var array<string, Organization>
     */
    private array $organizationById = [];

    public function insert(Organization $organization): void
    {
        $this->organizationById[$organization->getId()->unwrap()] = $organization;
    }

    public function findById(OrganizationId $id): ?Organization
    {
        return $this->organizationById[$id->unwrap()] ?? null;
    }

    public function existsById(OrganizationId $id): bool
    {
        return isset($this->organizationById[$id->unwrap()]);
    }
}
