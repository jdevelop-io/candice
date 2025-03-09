<?php

declare(strict_types=1);

namespace Candice\Organization\Application\GetOrganization;

final readonly class GetOrganizationResponse
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
