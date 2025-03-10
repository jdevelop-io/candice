<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Infrastructure\Repository;

use Candice\CustomerRelationship\Domain\Entity\Organization;
use Candice\CustomerRelationship\Domain\Entity\Prospect;
use Candice\CustomerRelationship\Domain\Factory\RegistrationNumberFactory;
use Candice\CustomerRelationship\Domain\Repository\ProspectRepositoryInterface;
use Candice\CustomerRelationship\Domain\ValueObject\RegistrationNumber;
use Candice\Organization\Infrastructure\Symfony\Service\GuidGeneratorInterface;
use Doctrine\DBAL\Connection;
use RuntimeException;

final readonly class DoctrineProspectRepository implements ProspectRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private RegistrationNumberFactory $registrationNumberFactory,
        private GuidGeneratorInterface $guidGenerator
    ) {
    }

    public function findById(string $id): ?Prospect
    {
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('crm_prospects')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return new Prospect(
            new Organization($data['organization_id']),
            $this->registrationNumberFactory->create($data['registration_number']),
            $data['name'],
            $data['id'],
        );
    }

    public function existsByRegistrationNumber(RegistrationNumber $registrationNumber): bool
    {
        $result = $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('crm_prospects')
            ->where('registration_number = :registration_number')
            ->setParameter('registration_number', $registrationNumber->unwrap())
            ->executeQuery();

        return (int)$result->fetchOne() > 0;
    }

    public function save(Prospect $prospect): void
    {
        if ($prospect->getId() === null) {
            $prospect->setId($this->guidGenerator->generate());
            $this->insert($prospect);
            return;
        }

        $this->update($prospect);
    }

    private function insert(Prospect $prospect): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->insert('crm_prospects')
            ->values([
                'id' => ':id',
                'organization_id' => ':organization_id',
                'registration_number' => ':registration_number',
                'name' => ':name',
            ])
            ->setParameters([
                'id' => $prospect->getId(),
                'organization_id' => $prospect->getOrganization()->getId(),
                'registration_number' => $prospect->getRegistrationNumber()->unwrap(),
                'name' => $prospect->getName(),
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new RuntimeException('Failed to save prospect');
        }
    }

    private function update(Prospect $prospect): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->update('crm_prospects')
            ->set('organization_id', ':organization_id')
            ->set('registration_number', ':registration_number')
            ->set('name', ':name')
            ->where('id = :id')
            ->setParameters([
                'organization_id' => $prospect->getOrganization()->getId(),
                'registration_number' => $prospect->getRegistrationNumber()->unwrap(),
                'name' => $prospect->getName(),
                'id' => $prospect->getId(),
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new RuntimeException('Failed to save prospect');
        }
    }
}
