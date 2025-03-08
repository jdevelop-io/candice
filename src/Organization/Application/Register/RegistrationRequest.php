<?php

declare(strict_types=1);

namespace Candice\Organization\Application\Register;

final readonly class RegistrationRequest
{
    public function __construct(private string $registrationNumber, private string $name)
    {
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
