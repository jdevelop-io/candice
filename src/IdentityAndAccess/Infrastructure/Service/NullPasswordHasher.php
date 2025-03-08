<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Service;

use Candice\IdentityAndAccess\Domain\Service\PasswordHasherInterface;

final readonly class NullPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plainPassword): string
    {
        return $plainPassword;
    }
}
