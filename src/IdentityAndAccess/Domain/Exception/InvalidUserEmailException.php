<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Exception;

use DomainException;

final class InvalidUserEmailException extends DomainException
{
    public function __construct(string $userEmail)
    {
        parent::__construct("Invalid user email: {$userEmail}");
    }
}
