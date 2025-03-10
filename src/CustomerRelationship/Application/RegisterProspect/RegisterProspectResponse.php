<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Application\RegisterProspect;

final readonly class RegisterProspectResponse
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
