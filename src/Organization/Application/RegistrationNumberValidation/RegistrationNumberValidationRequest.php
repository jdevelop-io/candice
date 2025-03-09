<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegistrationNumberValidation;

final readonly class RegistrationNumberValidationRequest
{
    public function __construct(private string $registrationNumber)
    {
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }
}
