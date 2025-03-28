<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Exception;

use DomainException;

final class InvalidExecutiveEmailException extends DomainException
{
    public function __construct(string $executiveEmail)
    {
        parent::__construct("Invalid executive email: $executiveEmail");
    }
}
