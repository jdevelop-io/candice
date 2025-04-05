<?php

declare(strict_types=1);

namespace Candice\Location\Infrastructure\Persistence;

use Candice\Location\Domain\Entity\Country;
use Candice\Location\Domain\Repository\CountryRepositoryInterface;
use Candice\Location\Domain\ValueObject\CountryCode;
use Override;

final class InMemoryCountryRepositoryInterface implements CountryRepositoryInterface
{
    /**
     * @var array<string, Country>
     */
    private array $countryByCode = [];

    /**
     * @param iterable<Country> $countries
     */
    public function __construct(iterable $countries = [])
    {
        foreach ($countries as $country) {
            $this->save($country);
        }
    }

    #[Override]
    public function save(Country $country): void
    {
        $this->countryByCode[$country->getCode()->unwrap()] = $country;
    }

    #[Override]
    public function existsByCode(CountryCode $countryCode): bool
    {
        return isset($this->countryByCode[$countryCode->unwrap()]);
    }

    public function findByCode(CountryCode $code): ?Country
    {
        return $this->countryByCode[$code->unwrap()] ?? null;
    }
}
