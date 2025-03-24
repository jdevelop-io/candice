<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Factory;

use Candice\Onboarding\Domain\Exception\UnsupportedRegistrationNumberTypeException;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;
use Candice\Onboarding\Domain\ValueObject\Siren;

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
