<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Service;

use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\Factory\UserFactory;

final readonly class UserRegistrationService
{
    public function __construct(private UserFactory $userFactory)
    {
    }

    public function register(string $userEmail, string $userFirstName, string $userLastName): User
    {
        return $this->userFactory->register($userEmail, $userFirstName, $userLastName);
    }
}
