<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\RegisterUser;

interface RegisterUserRequestInterface
{
    public function getUserEmail(): string;

    public function getUserFirstName(): string;

    public function getUserLastName(): string;
}
