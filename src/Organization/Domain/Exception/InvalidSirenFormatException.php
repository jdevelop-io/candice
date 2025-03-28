<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Exception;

use DomainException;

final class InvalidSirenFormatException extends DomainException
{
    public function __construct(string $siren)
    {
        parent::__construct("Invalid SIREN number: $siren. It must be a 9-digit number.");
    }
}
