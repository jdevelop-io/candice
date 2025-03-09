<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Repository;

use Candice\Onboarding\Domain\Entity\Application;

interface ApplicationRepositoryInterface
{
    public function existsByOrganizationRegistrationNumber(string $organizationRegistrationNumber): bool;

    public function findById(string $id): ?Application;

    public function save(Application $application): void;
}
