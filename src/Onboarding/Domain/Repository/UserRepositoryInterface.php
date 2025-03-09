<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Repository;

interface UserRepositoryInterface
{
    public function existsByEmail(string $email): bool;
}
