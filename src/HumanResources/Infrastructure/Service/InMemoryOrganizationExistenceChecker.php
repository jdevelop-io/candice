<?php

declare(strict_types=1);

namespace Candice\HumanResources\Infrastructure\Service;

use Candice\HumanResources\Domain\Service\OrganizationExistenceCheckerInterface;

final class InMemoryOrganizationExistenceChecker implements OrganizationExistenceCheckerInterface
{
    private array $organizationsIds = [];

    public function existsById(string $id): bool
    {
        return in_array($id, $this->organizationsIds, true);
    }

    public function add(string $id): void
    {
        $this->organizationsIds[] = $id;
    }
}
