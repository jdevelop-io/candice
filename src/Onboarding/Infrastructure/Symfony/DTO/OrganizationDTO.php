<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\DTO;

final readonly class OrganizationDTO
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
