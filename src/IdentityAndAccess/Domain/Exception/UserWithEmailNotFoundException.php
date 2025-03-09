<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Exception;

use Exception;

final class UserWithEmailNotFoundException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('User with email <%s> not found', $email));
    }
}
