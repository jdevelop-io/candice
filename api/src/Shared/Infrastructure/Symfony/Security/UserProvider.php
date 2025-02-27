<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Symfony\Security;

use Candice\Shared\Infrastructure\Symfony\Entity\User;
use Candice\Shared\Infrastructure\Symfony\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final readonly class UserProvider implements UserProviderInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findByEmail($identifier);

        if ($user) {
            return $user;
        }

        throw new UserNotFoundException();
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
