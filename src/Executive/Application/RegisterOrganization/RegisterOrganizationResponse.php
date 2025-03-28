<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterOrganization;

final readonly class RegisterOrganizationResponse
{
    public function __construct(private string $organizationId)
    {
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }
}
