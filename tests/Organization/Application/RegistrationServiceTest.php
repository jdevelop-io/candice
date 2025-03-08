<?php

declare(strict_types=1);

namespace Candice\Tests\Organization\Application;

use Candice\Organization\Application\Register\RegistrationRequest;
use Candice\Organization\Application\Register\RegistrationService;
use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\Exception\InvalidRegistrationNumberException;
use Candice\Organization\Domain\Exception\OrganizationAlreadyExistsException;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\ValueObject\Siren;
use Candice\Organization\Infrastructure\Repository\InMemoryOrganizationRepository;
use PHPUnit\Framework\TestCase;

final class RegistrationServiceTest extends TestCase
{
    private readonly RegistrationNumberFactory $registrationNumberFactory;
    private readonly RegistrationService $service;
    private readonly InMemoryOrganizationRepository $organizationRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrationNumberFactory = new RegistrationNumberFactory();
        $this->organizationRepository = new InMemoryOrganizationRepository();
        $this->service = new RegistrationService($this->organizationRepository, $this->registrationNumberFactory);
    }

    public function testRegistrationNumberShouldBeValidSiren(): void
    {
        $this->expectException(InvalidRegistrationNumberException::class);

        $request = new RegistrationRequest('InvalidSiren', 'Organization Name');
        $this->service->execute($request);
    }

    public function testRegistrationNumberShouldBeUnique(): void
    {
        $this->createOrganization('123456789', 'Organization Name');

        $this->expectException(OrganizationAlreadyExistsException::class);

        $request = new RegistrationRequest('123456789', 'Organization Name');
        $this->service->execute($request);
    }

    public function testOrganizationShouldBeRegistered(): void
    {
        $request = new RegistrationRequest('123456789', 'Organization Name');
        $response = $this->service->execute($request);

        $organization = $this->organizationRepository->findByRegistrationNumber(
            new Siren($response->getRegistrationNumber())
        );
        $this->assertNotNull($organization);
        $this->assertIsString($organization->getId());
        $this->assertSame('123456789', $organization->getRegistrationNumber()->unwrap());
        $this->assertSame('Organization Name', $organization->getName());
    }

    private function createOrganization(string $registrationNumber, string $name): void
    {
        $organization = new Organization(
            $this->organizationRepository->getNextId(),
            $this->registrationNumberFactory->create($registrationNumber),
            $name
        );

        $this->organizationRepository->save($organization);
    }
}
