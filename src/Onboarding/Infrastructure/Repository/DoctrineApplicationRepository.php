<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Enum\ApplicationStatus;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Candice\Organization\Infrastructure\Symfony\Service\GuidGeneratorInterface;
use Doctrine\DBAL\Connection;
use RuntimeException;

final readonly class DoctrineApplicationRepository implements ApplicationRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private GuidGeneratorInterface $guidGenerator
    ) {
    }

    public function existsByOrganizationRegistrationNumber(string $organizationRegistrationNumber): bool
    {
        $result = $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('onboarding_applications')
            ->where('organization_registration_number = :organization_registration_number')
            ->setParameter('organization_registration_number', $organizationRegistrationNumber)
            ->executeQuery();

        return (int)$result->fetchOne() > 0;
    }

    public function findById(string $id): ?Application
    {
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('onboarding_applications')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return new Application(
            $data['user_email'],
            $data['organization_registration_number'],
            $data['organization_name'],
            ApplicationStatus::from($data['status']),
            $data['id'],
        );
    }

    public function getNextId(): string
    {
        return $this->guidGenerator->generate();
    }

    public function save(Application $application): void
    {
        $isNew = false;

        if ($application->getId() === null) {
            $application->setId($this->guidGenerator->generate());
            $isNew = true;
        }

        if ($isNew) {
            $this->insert($application);
            return;
        }

        $this->update($application);
    }

    private function insert(Application $application): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->insert('onboarding_applications')
            ->values([
                'id' => ':id',
                'user_email' => ':user_email',
                'organization_registration_number' => ':organization_registration_number',
                'organization_name' => ':organization_name',
                'status' => ':status',
            ])
            ->setParameters([
                'id' => $application->getId(),
                'user_email' => $application->getUserEmail(),
                'organization_registration_number' => $application->getOrganizationRegistrationNumber(),
                'organization_name' => $application->getOrganizationName(),
                'status' => $application->getStatus()->value,
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new RuntimeException('Failed to create application');
        }
    }

    private function update(Application $application): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->update('onboarding_applications')
            ->set('user_email', ':user_email')
            ->set('organization_registration_number', ':organization_registration_number')
            ->set('organization_name', ':organization_name')
            ->set('status', ':status')
            ->where('id = :id')
            ->setParameters([
                'id' => $application->getId(),
                'user_email' => $application->getUserEmail(),
                'organization_registration_number' => $application->getOrganizationRegistrationNumber(),
                'organization_name' => $application->getOrganizationName(),
                'status' => $application->getStatus()->value,
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new RuntimeException('Failed to update application');
        }
    }
}
