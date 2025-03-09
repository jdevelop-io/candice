<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

final readonly class Organization
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
