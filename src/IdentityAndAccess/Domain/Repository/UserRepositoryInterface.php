<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Repository;

use Candice\IdentityAndAccess\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;

    public function getNextId(): string;

    public function save(User $user): void;
}
