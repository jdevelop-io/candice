<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Exception;

use Exception;

final class EmailAlreadyInUseException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('Email <%s> is already in use.', $email));
    }
}
