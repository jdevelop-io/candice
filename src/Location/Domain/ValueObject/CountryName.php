<?php

declare(strict_types=1);

namespace Candice\Location\Domain\ValueObject;

final readonly class CountryName
{
    public function __construct(private string $value)
    {
    }

    public function unwrap(): string
    {
        return $this->value;
    }
}
