<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\ValueObject;

use Candice\Executive\Domain\Exception\InvalidExecutiveEmailException;
use Stringable;

final readonly class ExecutiveEmail implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    public function unwrap(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidExecutiveEmailException($value);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
