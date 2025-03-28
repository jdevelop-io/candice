<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Repository;

use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;

final class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var array<string, User>
     */
    private array $userByEmail = [];

    public function insert(User $user): void
    {
        $this->userByEmail[$user->getEmail()->unwrap()] = $user;
    }

    public function existsByEmail(UserEmail $userEmail): bool
    {
        return isset($this->userByEmail[$userEmail->unwrap()]);
    }

    public function findByEmail(string $userEmail): ?User
    {
        return $this->userByEmail[$userEmail] ?? null;
    }
}
