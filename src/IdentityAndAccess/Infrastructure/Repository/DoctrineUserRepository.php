<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Repository;

use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\Enum\Role;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Candice\IdentityAndAccess\Infrastructure\Mapping\RoleMapper;
use Candice\Organization\Infrastructure\Symfony\Service\GuidGeneratorInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private GuidGeneratorInterface $guidGenerator,
        private RoleMapper $roleMapper
    ) {
    }

    public function findById(string $id): ?User
    {
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('iam_users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return new User(
            $data['id'],
            $data['email'],
            $data['password'],
            array_map(
                fn(string $role) => $this->roleMapper->toDomain($role),
                json_decode($data['roles'], true)
            )
        );
    }

    public function findByEmail(string $email): ?User
    {
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('iam_users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return new User(
            $data['id'],
            $data['email'],
            $data['password'],
            array_map(
                fn(string $role) => $this->roleMapper->toDomain($role),
                json_decode($data['roles'], true)
            )
        );
    }

    public function getNextId(): string
    {
        return $this->guidGenerator->generate();
    }

    public function save(User $user): void
    {
        $affectedRows = $this->connection->createQueryBuilder()
            ->insert('iam_users')
            ->values([
                'id' => ':id',
                'email' => ':email',
                'password' => ':password',
                'roles' => ':roles',
            ])
            ->setParameters([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'roles' => json_encode(
                    array_map(
                        fn(Role $role) => $this->roleMapper->toPersistence($role),
                        $user->getRoles()
                    )
                ),
            ])
            ->executeStatement();

        if ($affectedRows !== 1) {
            throw new \RuntimeException('User could not be saved.');
        }
    }
}
