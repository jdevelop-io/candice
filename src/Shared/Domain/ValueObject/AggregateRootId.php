<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Domain\ValueObject;

abstract class AggregateRootId
{
    private readonly string $value;

    public function __construct(
        string $value,
    ) {
        $this->value = $this->normalize($value);
    }

    private function normalize(string $value): string
    {
        return trim($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
