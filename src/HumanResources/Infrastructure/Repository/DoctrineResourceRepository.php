<?php

declare(strict_types=1);

namespace Candice\HumanResources\Infrastructure\Repository;

use Candice\HumanResources\Domain\Entity\Organization;
use Candice\HumanResources\Domain\Entity\Resource;
use Candice\HumanResources\Domain\Repository\ResourceRepositoryInterface;
use Candice\HumanResources\Domain\ValueObject\FullName;
use Candice\Organization\Infrastructure\Symfony\Service\GuidGeneratorInterface;
use Doctrine\DBAL\Connection;
use RuntimeException;

final readonly class DoctrineResourceRepository implements ResourceRepositoryInterface
{
    public function __construct(private Connection $connection, private GuidGeneratorInterface $guidGenerator)
    {
    }

    public function findById(string $id): ?Resource
    {
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('hr_resources')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return new Resource(
            new Organization($data['organization_id']),
            new FullName($data['first_name'], $data['last_name']),
            $data['id'],
        );
    }

    public function save(Resource $resource): void
    {
        if ($resource->getId() === null) {
            $resource->setId($this->guidGenerator->generate());
            $this->insert($resource);
        }

        $this->update($resource);
    }

    private function insert(Resource $resource): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->insert('hr_resources')
            ->values([
                'id' => ':id',
                'organization_id' => ':organization_id',
                'first_name' => ':first_name',
                'last_name' => ':last_name',
            ])
            ->setParameters([
                'id' => $resource->getId(),
                'organization_id' => $resource->getOrganization()->getId(),
                'first_name' => $resource->getFullName()->getFirstName(),
                'last_name' => $resource->getFullName()->getLastName(),
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new RuntimeException('Resource could not be inserted');
        }
    }

    private function update(Resource $resource): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->update('hr_resources')
            ->set('organization_id', ':organization_id')
            ->set('first_name', ':first_name')
            ->set('last_name', ':last_name')
            ->where('id = :id')
            ->setParameters([
                'id' => $resource->getId(),
                'organization_id' => $resource->getOrganization()->getId(),
                'first_name' => $resource->getFullName()->getFirstName(),
                'last_name' => $resource->getFullName()->getLastName(),
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new RuntimeException('Resource could not be updated');
        }
    }
}
