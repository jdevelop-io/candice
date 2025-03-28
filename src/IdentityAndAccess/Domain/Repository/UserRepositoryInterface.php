<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Repository;

use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;

interface UserRepositoryInterface
{
    public function existsByEmail(UserEmail $userEmail): bool;
}
