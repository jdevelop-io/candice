<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Mapping;

use Candice\IdentityAndAccess\Domain\Enum\Role;

final readonly class RoleMapper
{
    public function toPersistence(Role $role): string
    {
        return strtoupper('ROLE_' . $role->value);
    }

    public function toDomain(string $role): Role
    {
        return Role::from(strtolower(str_replace('ROLE_', '', $role)));
    }
}
