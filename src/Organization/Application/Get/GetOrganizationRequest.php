<?php

declare(strict_types=1);

namespace Candice\Organization\Application\Get;

final readonly class GetOrganizationRequest
{
    public function __construct(private string $registrationNumber)
    {
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }
}
