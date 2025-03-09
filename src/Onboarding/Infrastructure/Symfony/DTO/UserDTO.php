<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\DTO;

final readonly class UserDTO
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
