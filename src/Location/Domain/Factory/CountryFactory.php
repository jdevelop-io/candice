<?php

declare(strict_types=1);

namespace Candice\Location\Domain\Factory;

use Candice\Location\Domain\Entity\Country;
use Candice\Location\Domain\ValueObject\CountryCode;
use Candice\Location\Domain\ValueObject\CountryName;

final readonly class CountryFactory
{
    public function import(string $code, string $name): Country
    {
        return Country::import(new CountryCode($code), new CountryName($name));
    }
}
