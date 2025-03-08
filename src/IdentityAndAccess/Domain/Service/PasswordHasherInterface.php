<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Service;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): string;
}
