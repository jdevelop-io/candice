<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

final readonly class User
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
