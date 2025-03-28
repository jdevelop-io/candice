<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Application\RegisterUser;

use Candice\Tests\Unit\IdentityAndAccess\IdentityAndAccessTest;
use Candice\Tests\Unit\IdentityAndAccess\Traits\RegisterUserTestTrait;

final class RegisterUserServiceTest extends IdentityAndAccessTest
{
    use RegisterUserTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRegisterUserService();
    }
}
