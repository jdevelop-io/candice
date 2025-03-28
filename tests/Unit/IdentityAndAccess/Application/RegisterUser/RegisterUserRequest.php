<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Application\RegisterUser;

use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserRequestInterface;

final readonly class RegisterUserRequest implements RegisterUserRequestInterface
{
    public function __construct(private string $userEmail, private string $userFirstName, private string $userLastName)
    {
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getUserFirstName(): string
    {
        return $this->userFirstName;
    }

    public function getUserLastName(): string
    {
        return $this->userLastName;
    }
}
