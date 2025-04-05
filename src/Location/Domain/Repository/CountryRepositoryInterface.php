<?php

declare(strict_types=1);

namespace Candice\Location\Domain\Repository;

use Candice\Location\Domain\Entity\Country;
use Candice\Location\Domain\ValueObject\CountryCode;

interface CountryRepositoryInterface
{
    public function save(Country $country): void;

    public function existsByCode(CountryCode $countryCode): bool;
}
