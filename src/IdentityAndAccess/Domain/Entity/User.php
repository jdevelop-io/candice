<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Entity;

use Candice\IdentityAndAccess\Domain\Enum\Role;

final readonly class User
{
    /**
     * @param Role[] $roles
     */
    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private array $roles
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
