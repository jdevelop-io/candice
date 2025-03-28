<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\ValueObject;

final readonly class OrganizationName
{
    public function __construct(private string $value)
    {
    }

    public function unwrap(): string
    {
        return $this->value;
    }
}
