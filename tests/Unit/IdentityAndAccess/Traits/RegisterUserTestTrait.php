<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Traits;

use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserService;

trait RegisterUserTestTrait
{
    private RegisterUserService $service;

    protected function setUpRegisterUserService(): void
    {
        $this->service = new RegisterUserService();
    }
}
