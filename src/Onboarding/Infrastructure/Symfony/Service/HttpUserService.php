<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Service\UserServiceInterface;

final readonly class HttpUserService implements UserServiceInterface
{
    public function existsByEmail(string $email): bool
    {
        return false;
    }
}
