<?php

declare(strict_types=1);

namespace Candice\Location\Domain\Exception;

use Candice\Location\Domain\ValueObject\CountryCode;
use DomainException;

final class CountryAlreadyRegisteredException extends DomainException
{
    public function __construct(CountryCode $countryCode)
    {
        parent::__construct("Country with code {$countryCode} is already registered.");
    }
}
