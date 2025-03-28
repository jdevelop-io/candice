<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class InvalidSirenChecksumException extends DomainException
{
    public function __construct(string $siren)
    {
        parent::__construct("Invalid SIREN number: $siren. The checksum is invalid.");
    }
}
