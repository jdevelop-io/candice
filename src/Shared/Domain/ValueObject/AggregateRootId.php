<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\ValueObject;

abstract class AggregateRootId
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
}
