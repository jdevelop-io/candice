<?php

declare(strict_types=1);

namespace Candice\HumanResources\Domain\Repository;

use Candice\HumanResources\Domain\Entity\Resource;

interface ResourceRepositoryInterface
{
    public function findById(string $id): ?Resource;

    public function save(Resource $resource): void;
}
