<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Security;

use Candice\IdentityAndAccess\Infrastructure\Symfony\Entity\ApiUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final readonly class PrivateApiUserProvider implements UserProviderInterface
{
    public function __construct(private string $token)
    {
    }

    public function supportsClass(string $class): bool
    {
        return $class === ApiUser::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if ($identifier !== $this->token) {
            throw new UserNotFoundException();
        }

        return new ApiUser($identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof ApiUser) {
            throw new UnsupportedUserException();
        }

        return $user;
    }
}
