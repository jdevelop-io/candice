<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\ValueObject;

abstract class RegistrationNumber
{
    public function __construct(private string $value)
    {
    }

    abstract public static function matches(string $value): bool;

    public function unwrap(): string
    {
        return $this->value;
    }
}
