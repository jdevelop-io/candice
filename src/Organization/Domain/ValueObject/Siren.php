<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\ValueObject;

use Candice\Organization\Domain\Exception\InvalidSirenChecksumException;
use Candice\Organization\Domain\Exception\InvalidSirenFormatException;

use function Safe\preg_match;

final class Siren extends RegistrationNumber
{
    public const string TYPE = 'siren';
    private const string PATTERN = '/^\d{9}$/';

    public function __construct(string $value)
    {
        $this->validate($value);

        parent::__construct(self::TYPE, $value);
    }

    private function validate(string $value): void
    {
        if (!$this->isValidFormat($value)) {
            throw new InvalidSirenFormatException($value);
        }

        if (!$this->isValidChecksum($value)) {
            throw new InvalidSirenChecksumException($value);
        }
    }

    private function isValidFormat(string $value): bool
    {
        return preg_match(self::PATTERN, $value) === 1;
    }

    private function isValidChecksum(string $value): bool
    {
        $checksum = 0;
        $reversedValue = strrev($value);
        $length = strlen($reversedValue);

        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$reversedValue[$i];

            if ($i % 2 === 1) {
                $digit *= 2;
                $digit = $digit > 9 ? $digit - 9 : $digit;
            }

            $checksum += $digit;
        }

        return $checksum % 10 === 0;
    }
}
