<?php

declare(strict_types=1);

namespace Candice\Location\Domain\Factory;

use Candice\Location\Domain\Entity\Country;
use Candice\Location\Domain\ValueObject\CountryCode;
use Candice\Location\Domain\ValueObject\CountryName;

final readonly class CountryFactory
{
    public function register(string $code, string $name): Country
    {
        return Country::register(new CountryCode($code), new CountryName($name));
    }
}
