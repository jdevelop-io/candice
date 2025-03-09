<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\GetUserByEmail;

final readonly class GetUserByEmailRequest
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
