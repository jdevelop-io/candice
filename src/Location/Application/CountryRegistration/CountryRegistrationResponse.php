<?php

declare(strict_types=1);

namespace Candice\Location\Application\CountryRegistration;

final readonly class CountryRegistrationResponse
{
    public function __construct(private string $countryCode)
    {
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}
