<?php

declare(strict_types=1);

namespace Candice\Location\Application\CountryRegistration;

use Candice\Location\Domain\Entity\Country;
use Candice\Location\Domain\Exception\CountryAlreadyRegisteredException;
use Candice\Location\Domain\Factory\CountryFactory;
use Candice\Location\Domain\Repository\CountryRepositoryInterface;
use Candice\Location\Domain\ValueObject\CountryCode;

final readonly class CountryRegistration
{
    public function __construct(
        private CountryFactory $countryFactory,
        private CountryRepositoryInterface $countryRepository
    ) {
    }

    public function execute(CountryRegistrationRequestInterface $request): CountryRegistrationResponse
    {
        $this->guardAgainstAlreadyRegisteredCountry($request);

        $country = $this->registerCountry($request);

        return new CountryRegistrationResponse($country->getCode()->unwrap());
    }

    private function guardAgainstAlreadyRegisteredCountry(CountryRegistrationRequestInterface $request): void
    {
        $countryCode = new CountryCode($request->getCountryCode());

        if (!$this->countryRepository->existsByCode($countryCode)) {
            return;
        }

        throw new CountryAlreadyRegisteredException($countryCode);
    }

    private function registerCountry(CountryRegistrationRequestInterface $request): Country
    {
        $country = $this->countryFactory->register($request->getCountryCode(), $request->getCountryName());

        $this->countryRepository->save($country);

        return $country;
    }
}
