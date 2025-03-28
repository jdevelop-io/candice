<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Factory;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Executive\Domain\ValueObject\OrganizationName;

final readonly class OrganizationFactory
{
    public function create(OrganizationId $id, OrganizationName $name): Organization
    {
        return new Organization($id, $name);
    }
}
