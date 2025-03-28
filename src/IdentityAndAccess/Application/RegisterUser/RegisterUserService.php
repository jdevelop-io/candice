<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\RegisterUser;

final readonly class RegisterUserService
{
    public function execute(RegisterUserRequestInterface $request): RegisterUserResponse
    {
        return new RegisterUserResponse();
    }
}
