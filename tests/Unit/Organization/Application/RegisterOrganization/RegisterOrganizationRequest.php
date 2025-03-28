<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization\Application\RegisterOrganization;

use Candice\Organization\Application\RegisterOrganization\RegisterOrganizationRequestInterface;

final readonly class RegisterOrganizationRequest implements RegisterOrganizationRequestInterface
{
    public function __construct(
        private string $organizationRegistrationNumberType,
        private string $organizationRegistrationNumber,
        private string $organizationName,
    ) {
    }

    public function getOrganizationRegistrationNumberType(): string
    {
        return $this->organizationRegistrationNumberType;
    }

    public function getOrganizationRegistrationNumber(): string
    {
        return $this->organizationRegistrationNumber;
    }

    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }
}
