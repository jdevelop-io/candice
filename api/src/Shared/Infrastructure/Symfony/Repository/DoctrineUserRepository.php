<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Symfony\Repository;

use Candice\Shared\Infrastructure\Symfony\Entity\User;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function findByEmail(string $email): ?UserInterface
    {
        $result = $this->connection->createQueryBuilder()
            ->select('id', 'email', 'password', 'roles')
            ->from('shared_user')
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
            json_decode($data['roles'], true)
        );
    }
}
