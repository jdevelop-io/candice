<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Traits;

use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserResponse;
use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserService;
use Candice\IdentityAndAccess\Infrastructure\Repository\InMemoryUserRepository;
use Candice\Tests\Unit\IdentityAndAccess\Application\RegisterUser\RegisterUserRequest;

trait RegisterUserTestTrait
{
    private RegisterUserService $service;
    private InMemoryUserRepository $userRepository;

    protected function setUpRegisterUserService(): void
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->service = new RegisterUserService($this->userRepository);
    }

    protected function registerUser(string $userEmail): RegisterUserResponse
    {
        $request = new RegisterUserRequest(
            $userEmail,
        );

        return $this->service->execute($request);
    }
}
