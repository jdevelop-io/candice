<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Repository;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Enum\ApplicationStatus;

interface ApplicationRepositoryInterface
{
    public function existsByOrganizationRegistrationNumber(string $organizationRegistrationNumber): bool;

    /**
     * @return Application[]
     */
    public function findAll(): array;

    /**
     * @param ApplicationStatus $status
     * @return Application[]
     */
    public function findAllByStatus(ApplicationStatus $status): array;

    public function findById(string $id): ?Application;

    public function save(Application $application): void;
}
