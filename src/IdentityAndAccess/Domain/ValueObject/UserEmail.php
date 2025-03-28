<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\ValueObject;


use Candice\IdentityAndAccess\Domain\Exception\InvalidUserEmailException;

final readonly class UserEmail
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
            throw new InvalidUserEmailException($value);
        }
    }
}
