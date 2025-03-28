<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\RegisterUser;

final readonly class RegisterUserResponse
{
    public function __construct(private string $userEmail)
    {
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }
}
