<?php

declare(strict_types=1);

namespace Candice\Location\Application\CountryRegistration;

interface CountryRegistrationRequestInterface
{
    public function getCountryCode(): string;

    public function getCountryName(): string;
}
