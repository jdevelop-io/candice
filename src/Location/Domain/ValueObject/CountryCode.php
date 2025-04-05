<?php

declare(strict_types=1);

namespace Candice\Location\Domain\ValueObject;

use Candice\Location\Domain\Exception\InvalidCountryCodeException;
use Override;
use Stringable;

final readonly class CountryCode implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        $sanitizedValue = strtoupper($value);

        $this->validate($sanitizedValue);

        $this->value = $sanitizedValue;
    }

    public function unwrap(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (!preg_match('/^[A-Z]{2}$/', $value)) {
            throw new InvalidCountryCodeException($value);
        }
    }

    #[Override]
    public function __toString(): string
    {
        return $this->value;
    }
}
