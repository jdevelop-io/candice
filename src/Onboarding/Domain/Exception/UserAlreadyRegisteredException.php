<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Exception;

final class UserAlreadyRegisteredException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('User with email <%s> is already registered', $email));
    }
}
