<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Repository;

interface OrganizationRepositoryInterface
{
    public function existsByRegistrationNumber(string $registrationNumber): bool;
}
