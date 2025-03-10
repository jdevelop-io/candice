<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Entity;

final readonly class Organization
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
