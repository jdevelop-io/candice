<?php

declare(strict_types=1);

namespace Candice\HumanResources\Domain\Service;

interface OrganizationExistenceCheckerInterface
{
    public function existsById(string $id): bool;
}
