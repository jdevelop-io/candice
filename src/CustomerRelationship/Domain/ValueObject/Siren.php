<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\ValueObject;

use Candice\CustomerRelationship\Domain\Exception\InvalidSirenException;

final class Siren extends RegistrationNumber
{
    public function __construct(string $value)
    {
        $this->validate($value);

        parent::__construct($value);
    }

    public static function matches(string $value): bool
    {
        return preg_match('/^\d{9}$/', $value) === 1;
    }

    private function validate(string $value): void
    {
        if (!self::matches($value)) {
            throw new InvalidSirenException($value);
        }
    }
}
