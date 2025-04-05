<?php

declare(strict_types=1);

namespace Candice\Location\Application\CountryImportation;

interface CountryImportationRequestInterface
{
    public function getCountryCode(): string;

    public function getCountryName(): string;
}
