<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Location\Application\CountryImportation;

use Candice\Location\Application\CountryImportation\CountryImportationRequestInterface;
use Override;

final readonly class CountryImportationRequest implements CountryImportationRequestInterface
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
