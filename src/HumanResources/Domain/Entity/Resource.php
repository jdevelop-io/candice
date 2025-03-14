<?php

declare(strict_types=1);

namespace Candice\HumanResources\Domain\Entity;

use Candice\HumanResources\Domain\ValueObject\FullName;

final class Resource
{
    public function __construct(
        private readonly Organization $organization,
        private readonly FullName $fullName,
        private ?string $id = null
    ) {
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getFullName(): FullName
    {
        return $this->fullName;
    }
}
