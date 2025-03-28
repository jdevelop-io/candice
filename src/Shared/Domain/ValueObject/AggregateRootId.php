<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\ValueObject;

use Stringable;

abstract class AggregateRootId implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function unwrap(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->unwrap();
    }
}
