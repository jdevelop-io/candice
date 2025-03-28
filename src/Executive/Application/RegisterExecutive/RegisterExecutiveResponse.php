<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterExecutive;

final readonly class RegisterExecutiveResponse
{
    public function __construct(private string $executiveEmail)
    {
    }

    public function getExecutiveEmail(): string
    {
        return $this->executiveEmail;
    }
}
