<?php

declare(strict_types=1);

namespace Candice\Location\Application\CountryImportation;

final readonly class CountryImportationResponse
{
    public function __construct(private string $countryCode)
    {
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}
