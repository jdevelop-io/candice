<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Factory;

use Candice\CustomerRelationship\Domain\Exception\InvalidRegistrationNumberException;
use Candice\CustomerRelationship\Domain\ValueObject\RegistrationNumber;
use Candice\CustomerRelationship\Domain\ValueObject\Siren;

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
