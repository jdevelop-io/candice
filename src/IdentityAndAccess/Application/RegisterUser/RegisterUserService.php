<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\RegisterUser;

use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;

final readonly class RegisterUserService
{
    public function execute(RegisterUserRequestInterface $request): RegisterUserResponse
    {
        $userEmail = new UserEmail($request->getUserEmail());
        return new RegisterUserResponse();
    }
}
