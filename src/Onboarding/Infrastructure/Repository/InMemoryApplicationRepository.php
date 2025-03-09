<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;

final class InMemoryApplicationRepository implements ApplicationRepositoryInterface
{

    /**
     * @var array<string, Application>
     */
    private array $applicationById = [];

    /**
     * @var array<string, Application[]>
     */
    private array $applicationByOrganizationRegistrationNumber = [];

    private int $nextId = 1;

    public function existsByOrganizationRegistrationNumber(string $organizationRegistrationNumber): bool
    {
        return isset($this->applicationByOrganizationRegistrationNumber[$organizationRegistrationNumber]);
    }

    public function save(Application $application): void
    {
        if ($application->getId() === null) {
            $application->setId((string)$this->nextId++);
        }

        $this->applicationById[$application->getId()] = $application;
        $this->applicationByOrganizationRegistrationNumber[$application->getOrganizationRegistrationNumber()][]
            = $application;
    }

    public function findById(string $id): ?Application
    {
        return $this->applicationById[$id] ?? null;
    }
}
