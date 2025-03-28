<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Application\RegisterExecutive;

use Candice\Executive\Application\RegisterExecutive\RegisterExecutiveRequestInterface;

final readonly class RegisterExecutiveRequest implements RegisterExecutiveRequestInterface
{
    public function __construct(
        private string $organizationId,
        private string $executiveEmail,
        private string $executiveFirstName,
        private string $executiveLastName
    ) {
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getExecutiveEmail(): string
    {
        return $this->executiveEmail;
    }

    public function getExecutiveFirstName(): string
    {
        return $this->executiveFirstName;
    }

    public function getExecutiveLastName(): string
    {
        return $this->executiveLastName;
    }
}
