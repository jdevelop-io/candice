<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Traits;

use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserResponse;
use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserService;
use Candice\Tests\Unit\IdentityAndAccess\Application\RegisterUser\RegisterUserRequest;

trait RegisterUserTestTrait
{
    private RegisterUserService $service;

    protected function setUpRegisterUserService(): void
    {
        $this->service = new RegisterUserService();
    }

    protected function registerUser(string $userEmail): RegisterUserResponse
    {
        $request = new RegisterUserRequest(
            $userEmail,
        );

        return $this->service->execute($request);
    }
}
