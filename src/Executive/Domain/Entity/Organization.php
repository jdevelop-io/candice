<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Entity;

use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Executive\Domain\ValueObject\OrganizationName;

final readonly class Organization
{
    public function __construct(private OrganizationId $id, private OrganizationName $name)
    {
    }

    public function getId(): OrganizationId
    {
        return $this->id;
    }

    public function getName(): OrganizationName
    {
        return $this->name;
    }
}
