<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\RegisterUser;

use Candice\IdentityAndAccess\Domain\Exception\UserAlreadyRegisteredException;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;

final readonly class RegisterUserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(RegisterUserRequestInterface $request): RegisterUserResponse
    {
        $userEmail = new UserEmail($request->getUserEmail());

        if ($this->userRepository->existsByEmail($userEmail)) {
            throw new UserAlreadyRegisteredException($userEmail);
        }

        return new RegisterUserResponse();
    }
}
