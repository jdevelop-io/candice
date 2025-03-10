<?php

declare(strict_types=1);

namespace Candice\Tests\CustomerRelationship\Application;

use Candice\CustomerRelationship\Application\RegisterProspect\RegisterProspectRequest;
use Candice\CustomerRelationship\Application\RegisterProspect\RegisterProspectService;
use Candice\CustomerRelationship\Domain\Entity\Organization;
use Candice\CustomerRelationship\Domain\Entity\Prospect;
use Candice\CustomerRelationship\Domain\Exception\InvalidRegistrationNumberException;
use Candice\CustomerRelationship\Domain\Exception\OrganizationNotFoundException;
use Candice\CustomerRelationship\Domain\Exception\ProspectAlreadyRegisteredException;
use Candice\CustomerRelationship\Domain\Factory\RegistrationNumberFactory;
use Candice\CustomerRelationship\Infrastructure\Repository\InMemoryProspectRepository;
use Candice\CustomerRelationship\Infrastructure\Service\InMemoryOrganizationChecker;
use PHPUnit\Framework\TestCase;

final class RegisterProspectServiceTest extends TestCase
{
    private readonly InMemoryOrganizationChecker $organizationChecker;
    private readonly InMemoryProspectRepository $prospectRepository;
    private readonly RegistrationNumberFactory $registrationNumberFactory;
    private readonly RegisterProspectService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organizationChecker = new InMemoryOrganizationChecker();
        $this->prospectRepository = new InMemoryProspectRepository();
        $this->registrationNumberFactory = new RegistrationNumberFactory();
        $this->service = new RegisterProspectService(
            $this->prospectRepository,
            $this->organizationChecker,
            $this->registrationNumberFactory
        );

        $this->createOrganization('ExistingOrganizationId');
    }

    public function testInvalidOrganization(): void
    {
        $this->expectException(OrganizationNotFoundException::class);

        $request = new RegisterProspectRequest('InvalidOrganizationId', '123456789', 'Organization Name');
        $this->service->execute($request);
    }

    public function testRegistrationNumberShouldBeValidSiren(): void
    {
        $this->expectException(InvalidRegistrationNumberException::class);

        $request = new RegisterProspectRequest('ExistingOrganizationId', 'InvalidSiren', 'Organization Name');
        $this->service->execute($request);
    }

    public function testRegistrationNumberShouldBeUnique(): void
    {
        $this->createProspect('ExistingOrganization', '123456789', 'Organization Name');

        $this->expectException(ProspectAlreadyRegisteredException::class);

        $request = new RegisterProspectRequest('ExistingOrganizationId', '123456789', 'Organization Name');
        $this->service->execute($request);
    }

    public function testProspectShouldBeRegistered(): void
    {
        $request = new RegisterProspectRequest('ExistingOrganizationId', '123456789', 'Organization Name');
        $response = $this->service->execute($request);

        $prospect = $this->prospectRepository->findById($response->getId());
        $this->assertNotNull($prospect);
        $this->assertSame('ExistingOrganizationId', $prospect->getOrganization()->getId());
        $this->assertSame('123456789', $prospect->getRegistrationNumber()->unwrap());
        $this->assertSame('Organization Name', $prospect->getName());
    }

    private function createProspect(string $organizationId, string $registrationNumber, string $name): void
    {
        $prospect = new Prospect(
            new Organization($organizationId),
            $this->registrationNumberFactory->create($registrationNumber),
            $name
        );

        $this->prospectRepository->save($prospect);
    }

    private function createOrganization(string $id): void
    {
        $organization = new Organization($id);

        $this->organizationChecker->save($organization);
    }
}
