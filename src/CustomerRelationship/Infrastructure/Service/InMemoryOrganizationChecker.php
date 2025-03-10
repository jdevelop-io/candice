<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Infrastructure\Service;

use Candice\CustomerRelationship\Domain\Entity\Organization;
use Candice\CustomerRelationship\Domain\Service\OrganizationCheckerInterface;

final class InMemoryOrganizationChecker implements OrganizationCheckerInterface
{
    private array $organizationById = [];

    public function existsById(string $id): bool
    {
        return isset($this->organizationById[$id]);
    }

    public function save(Organization $organization): void
    {
        $this->organizationById[$organization->getId()] = $organization;
    }
}
