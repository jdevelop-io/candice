<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use DomainException;

final class UnsupportedRegistrationNumberTypeException extends DomainException
{
    public function __construct(string $type)
    {
        parent::__construct("Unsupported registration number type: $type");
    }
}
