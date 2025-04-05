<?php

declare(strict_types=1);

namespace Candice\Location\Domain\Exception;

use DomainException;

final class InvalidCountryCodeException extends DomainException
{
    public function __construct(string $countryCode)
    {
        parent::__construct(
            "Invalid country code: {$countryCode}. It must be a 2-letter ISO 3166-1 or ISO 3166-2 alpha-2 code."
        );
    }
}
