<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Service;

use Candice\Onboarding\Domain\Service\UserServiceInterface;

final class InMemoryUserService implements UserServiceInterface
{
    /**
     * @var string[]
     */
    private array $usersEmails = [];

    public function add(string $email): void
    {
        $this->usersEmails[] = $email;
    }

    public function existsByEmail(string $email): bool
    {
        return in_array($email, $this->usersEmails, true);
    }
}
