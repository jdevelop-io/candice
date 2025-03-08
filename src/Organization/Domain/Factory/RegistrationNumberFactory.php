<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Factory;

use Candice\Organization\Domain\Exception\InvalidRegistrationNumberException;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;
use Candice\Organization\Domain\ValueObject\Siren;

final readonly class RegistrationNumberFactory
{
    public function create(string $registrationNumber): RegistrationNumber
    {
        if (Siren::matches($registrationNumber)) {
            return new Siren($registrationNumber);
        }

        throw new InvalidRegistrationNumberException($registrationNumber);
    }
}
