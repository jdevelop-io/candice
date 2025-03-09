<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\User;
use Candice\Onboarding\Domain\Repository\UserRepositoryInterface;

final class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var array<string, User>
     */
    private array $userByEmail = [];

    public function existsByEmail(string $email): bool
    {
        return isset($this->userByEmail[$email]);
    }

    public function save(User $user): void
    {
        $this->userByEmail[$user->getEmail()] = $user;
    }
}
