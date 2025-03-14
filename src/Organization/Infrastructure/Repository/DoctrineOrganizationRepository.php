<?php

declare(strict_types=1);

namespace Candice\Organization\Infrastructure\Repository;

use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;
use Doctrine\DBAL\Connection;
use RuntimeException;

final readonly class DoctrineOrganizationRepository implements OrganizationRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private RegistrationNumberFactory $registrationNumberFactory
    ) {
    }

    public function existsByRegistrationNumber(RegistrationNumber $registrationNumber): bool
    {
        $result = $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('organization_organizations')
            ->where('registration_number = :registration_number')
            ->setParameter('registration_number', $registrationNumber->unwrap())
            ->executeQuery();

        return (int)$result->fetchOne() > 0;
    }

    public function findByRegistrationNumber(RegistrationNumber $registrationNumber): ?Organization
    {
        $result = $this->connection->createQueryBuilder()
            ->select('registration_number', 'name')
            ->from('organization_organizations')
            ->where('registration_number = :registration_number')
            ->setParameter('registration_number', $registrationNumber->unwrap())
            ->executeQuery();

        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return new Organization(
            $this->registrationNumberFactory->create($data['registration_number']),
            $data['name'],
        );
    }

    public function save(Organization $organization): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->insert('organization_organizations')
            ->values([
                'registration_number' => ':registration_number',
                'name' => ':name',
            ])
            ->setParameters([
                'registration_number' => $organization->getRegistrationNumber()->unwrap(),
                'name' => $organization->getName(),
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new RuntimeException('Failed to save organization');
        }
    }
}
