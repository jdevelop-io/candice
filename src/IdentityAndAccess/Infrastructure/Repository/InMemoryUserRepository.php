<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Repository;

use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;

final class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var array<string, User>
     */
    private array $userById = [];

    /**
     * @var array<string, User>
     */
    private array $userByEmail = [];

    private int $nextId = 1;

    public function findById(string $id): ?User
    {
        return $this->userById[$id] ?? null;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userByEmail[$email] ?? null;
    }

    public function getNextId(): string
    {
        return (string)$this->nextId++;
    }

    public function save(User $user): void
    {
        $this->userById[$user->getId()] = $user;
        $this->userByEmail[$user->getEmail()] = $user;
    }
}
