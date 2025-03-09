<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Security;

use Candice\IdentityAndAccess\Domain\Enum\Role;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Candice\IdentityAndAccess\Infrastructure\Mapping\RoleMapper;
use Candice\IdentityAndAccess\Infrastructure\Symfony\Entity\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public function __construct(private UserRepositoryInterface $userRepository, private RoleMapper $roleMapper)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findByEmail($identifier);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return new User(
            $user->getId(),
            $user->getEmail(),
            $user->getPassword(),
            array_map(
                fn(Role $role) => $this->roleMapper->toPersistence($role),
                $user->getRoles()
            )
        );
    }
}
