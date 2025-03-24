<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class InvalidSirenFormatException extends DomainException
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('Invalid SIREN number: %s. It must be a 9-digit number.', $value));
    }
}
