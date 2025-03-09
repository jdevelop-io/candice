<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class ApiUser implements UserInterface
{
    /**
     * @var string[]
     */
    private array $roles;

    public function __construct(private string $identifier)
    {
        $this->roles = ['ROLE_API'];
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }
}
