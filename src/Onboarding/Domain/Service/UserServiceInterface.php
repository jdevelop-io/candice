<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Service;

interface UserServiceInterface
{
    public function existsByEmail(string $email): bool;
}
