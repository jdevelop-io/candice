<?php

declare(strict_types=1);

namespace Candice\HumanResources\Application\RegisterResource;

final readonly class RegisterResourceResponse
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
