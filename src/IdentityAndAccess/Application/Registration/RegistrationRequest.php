<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\Registration;

final readonly class RegistrationRequest
{
    public function __construct(private string $email, private string $plainPassword)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
