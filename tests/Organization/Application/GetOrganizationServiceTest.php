<?php

declare(strict_types=1);

namespace Candice\Tests\Organization\Application;

use Candice\Organization\Application\Get\GetOrganizationRequest;
use Candice\Organization\Application\Get\GetOrganizationService;
use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\Exception\OrganizationNotFoundException;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Infrastructure\Repository\InMemoryOrganizationRepository;
use PHPUnit\Framework\TestCase;

final class GetOrganizationServiceTest extends TestCase
{
    private readonly InMemoryOrganizationRepository $organizationRepository;
    private readonly RegistrationNumberFactory $registrationNumberFactory;
    private readonly GetOrganizationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organizationRepository = new InMemoryOrganizationRepository();
        $this->registrationNumberFactory = new RegistrationNumberFactory();
        $this->service = new GetOrganizationService($this->organizationRepository, $this->registrationNumberFactory);
    }

    public function testOrganizationShouldExists(): void
    {
        $this->expectException(OrganizationNotFoundException::class);

        $this->service->execute(new GetOrganizationRequest('123456789'));
    }

    public function testOrganizationIsReturned(): void
    {
        $this->createOrganization('123456789', 'Organization Name');

        $response = $this->service->execute(new GetOrganizationRequest('123456789'));

        $this->assertSame('123456789', $response->getRegistrationNumber());
        $this->assertSame('Organization Name', $response->getName());
    }

    private function createOrganization(string $registrationNumber, string $name): void
    {
        $organization = new Organization(
            $this->registrationNumberFactory->create($registrationNumber),
            $name
        );

        $this->organizationRepository->save($organization);
    }
}
