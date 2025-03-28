<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Repository;

use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;

final class InMemoryUserRepository implements UserRepositoryInterface
{
    private bool $exists = false;

    public function existsByEmail(UserEmail $userEmail): bool
    {
        if ($this->exists) {
            return true;
        }

        $this->exists = true;

        return false;
    }
}
