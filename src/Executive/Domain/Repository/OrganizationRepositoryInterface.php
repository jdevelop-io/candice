<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Repository;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\ValueObject\OrganizationId;

interface OrganizationRepositoryInterface
{
    public function insert(Organization $organization): void;

    public function findById(OrganizationId $id): ?Organization;

    public function existsById(OrganizationId $id): bool;
}
