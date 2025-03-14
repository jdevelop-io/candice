<?php

declare(strict_types=1);

namespace Candice\HumanResources\Application\RegisterResource;

final readonly class RegisterResourceRequest
{
    public function __construct(private string $organizationId, private string $firstName, private string $lastName)
    {
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
