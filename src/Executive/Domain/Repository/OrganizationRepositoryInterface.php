<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Repository;

use Candice\Executive\Domain\ValueObject\OrganizationId;

interface OrganizationRepositoryInterface
{
    public function existsById(OrganizationId $id): bool;
}
