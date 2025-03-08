<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Entity;

use Candice\Organization\Domain\ValueObject\RegistrationNumber;

final readonly class Organization
{
    public function __construct(private RegistrationNumber $registrationNumber, private string $name)
    {
    }

    public function getRegistrationNumber(): RegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
