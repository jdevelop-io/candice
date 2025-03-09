<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\GetUserByEmail;

final readonly class GetUserByEmailResponse
{
    public function __construct(private string $id, private string $email)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
