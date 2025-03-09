<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Enum\ApplicationStatus;
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

    /**
     * @var array<string, Application[]>
     */
    private array $applicationByStatus = [];

    private int $nextId = 1;

    public function findById(string $id): ?Application
    {
        return $this->applicationById[$id] ?? null;
    }

    /**
     * @return Application[]
     */
    public function findAll(): array
    {
        return array_values($this->applicationById);
    }

    /**
     * @param ApplicationStatus $status
     * @return Application[]
     */
    public function findAllByStatus(ApplicationStatus $status): array
    {
        return $this->applicationByStatus[$status->value] ?? [];
    }

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
        $this->applicationByStatus[$application->getStatus()->value][] = $application;
    }
}
