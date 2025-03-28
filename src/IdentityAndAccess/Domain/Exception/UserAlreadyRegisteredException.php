<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Exception;

use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;
use DomainException;

final class UserAlreadyRegisteredException extends DomainException
{
    public function __construct(UserEmail $userEmail)
    {
        parent::__construct("A user with the email $userEmail is already registered");
    }
}
