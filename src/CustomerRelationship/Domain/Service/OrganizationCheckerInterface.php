<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Service;

interface OrganizationCheckerInterface
{
    public function existsById(string $id): bool;
}
