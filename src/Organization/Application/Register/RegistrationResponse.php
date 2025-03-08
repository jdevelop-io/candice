<?php

declare(strict_types=1);

namespace Candice\Organization\Application\Register;

final readonly class RegistrationResponse
{
    public function __construct(private string $registrationNumber)
    {
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }
}
