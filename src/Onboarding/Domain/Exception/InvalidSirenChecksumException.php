<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class InvalidSirenChecksumException extends DomainException
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('Invalid SIREN number: %s. The checksum is invalid.', $value));
    }
}
