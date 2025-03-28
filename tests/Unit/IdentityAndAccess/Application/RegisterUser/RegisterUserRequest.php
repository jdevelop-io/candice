<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Application\RegisterUser;

use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserRequestInterface;

final readonly class RegisterUserRequest implements RegisterUserRequestInterface
{
    public function __construct(private string $userEmail)
    {
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }
}
