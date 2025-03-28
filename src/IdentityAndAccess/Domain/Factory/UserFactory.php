<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Factory;

use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;
use Candice\IdentityAndAccess\Domain\ValueObject\UserFullName;

final readonly class UserFactory
{
    public function register(string $email, string $firstName, string $lastName): User
    {
        return User::register(new UserEmail($email), new UserFullName($firstName, $lastName));
    }
}
