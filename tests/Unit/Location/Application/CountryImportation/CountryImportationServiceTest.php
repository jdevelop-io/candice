<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Location\Application\CountryImportation;

use Candice\Location\Application\CountryImportation\CountryImportation;
use Candice\Location\Domain\Exception\CountryAlreadyRegisteredException;
use Candice\Location\Domain\Exception\InvalidCountryCodeException;
use Candice\Location\Domain\Factory\CountryFactory;
use Candice\Location\Domain\ValueObject\CountryCode;
use Candice\Location\Infrastructure\Persistence\InMemoryCountryRepositoryInterface;
use Override;
use PHPUnit\Framework\TestCase;

final class CountryImportationServiceTest extends TestCase
{
    private CountryFactory $countryFactory;
    private InMemoryCountryRepositoryInterface $countryRepository;
    private CountryImportation $service;

    #[Override]
    protected function setUp(): void
    {
        $this->countryFactory = new CountryFactory();
        $this->countryRepository = new InMemoryCountryRepositoryInterface();
        $this->service = new CountryImportation($this->countryFactory, $this->countryRepository);
    }

    /**
     * @dataProvider invalidCountryCodeProvider
     */
    public function testInvalidCountryCode(string $countryCode): void
    {
        $this->expectException(InvalidCountryCodeException::class);

        $this->service->execute(new CountryImportationRequest($countryCode, 'Country Name'));
    }

    public function testCountryAlreadyRegistered(): void
    {
        $this->expectException(CountryAlreadyRegisteredException::class);

        $country = $this->countryFactory->import('fr', 'France');
        $this->countryRepository->save($country);

        $this->service->execute(new CountryImportationRequest('fr', 'France'));
    }

    public function testRequirementsAreMet(): void
    {
        $request = new CountryImportationRequest('fr', 'France');

        $response = $this->service->execute($request);

        $country = $this->countryRepository->findByCode(new CountryCode($response->getCountryCode()));

        $this->assertNotNull($country);
        $this->assertSame('FR', $country->getCode()->unwrap());
        $this->assertSame('France', $country->getName()->unwrap());
    }

    /**
     * @return string[][]
     *
     * @psalm-return list{list{'France'}, list{'FRA'}}
     */
    protected function invalidCountryCodeProvider(): array
    {
        return [
            ['France'],
            ['FRA'],
        ];
    }
}
