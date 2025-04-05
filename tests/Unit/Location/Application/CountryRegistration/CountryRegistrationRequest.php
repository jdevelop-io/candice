<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Location\Application\CountryRegistration;

use Candice\Location\Application\CountryRegistration\CountryRegistrationRequestInterface;
use Override;

final readonly class CountryRegistrationRequest implements CountryRegistrationRequestInterface
{
    public function __construct(private string $countryCode, private string $countryName)
    {
    }

    #[Override]
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    #[Override]
    public function getCountryName(): string
    {
        return $this->countryName;
    }
}
