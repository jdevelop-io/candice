<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Application\RegisterOrganization;

use Candice\Executive\Application\RegisterOrganization\RegisterOrganizationRequestInterface;

final readonly class RegisterOrganizationRequest implements RegisterOrganizationRequestInterface
{
    public function __construct(private string $organizationId, private string $organizationName)
    {
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }
}
