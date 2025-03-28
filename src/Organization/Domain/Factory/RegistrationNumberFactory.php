<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Factory;

use Candice\Organization\Domain\Exception\UnsupportedRegistrationNumberTypeException;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;
use Candice\Organization\Domain\ValueObject\Siren;

final readonly class RegistrationNumberFactory
{
    public function create(string $type, string $value): RegistrationNumber
    {
        return match ($type) {
            Siren::TYPE => new Siren($value),
            default => throw new UnsupportedRegistrationNumberTypeException($type),
        };
    }
}
