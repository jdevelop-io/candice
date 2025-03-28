<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Application\RegisterUser;

use Candice\IdentityAndAccess\Domain\Exception\InvalidUserEmailException;
use Candice\IdentityAndAccess\Domain\Exception\UserAlreadyRegisteredException;
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

    public function testInvalidUserEmail(): void
    {
        $this->expectException(InvalidUserEmailException::class);

        $this->registerUser(
            'paul-henry.dumont',
        );
    }

    public function testUserEmailIsUnique(): void
    {
        $this->registerUser(
            'paul-henry.dumont@example.com',
        );

        $this->expectException(UserAlreadyRegisteredException::class);

        $this->registerUser(
            'paul-henry.dumont@example.com',
        );
    }
}
