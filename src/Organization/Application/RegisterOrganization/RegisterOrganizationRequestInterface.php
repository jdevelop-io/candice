<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegisterOrganization;

interface RegisterOrganizationRequestInterface
{
    public function getOrganizationRegistrationNumberType(): string;

    public function getOrganizationRegistrationNumber(): string;

    public function getOrganizationName(): string;
}
