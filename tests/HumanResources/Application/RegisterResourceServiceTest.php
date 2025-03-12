<?php

declare(strict_types=1);

namespace Candice\Tests\HumanResources\Application;

use Candice\HumanResources\Application\RegisterResource\RegisterResourceRequest;
use Candice\HumanResources\Application\RegisterResource\RegisterResourceService;
use Candice\HumanResources\Domain\Exception\OrganizationNotFoundException;
use Candice\HumanResources\Infrastructure\Repository\InMemoryResourceRepository;
use Candice\HumanResources\Infrastructure\Service\InMemoryOrganizationExistenceChecker;
use PHPUnit\Framework\TestCase;

final class RegisterResourceServiceTest extends TestCase
{
    private readonly InMemoryResourceRepository $resourceRepository;
    private readonly InMemoryOrganizationExistenceChecker $organizationExistenceChecker;
    private readonly RegisterResourceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceRepository = new InMemoryResourceRepository();
        $this->organizationExistenceChecker = new InMemoryOrganizationExistenceChecker();
        $this->service = new RegisterResourceService($this->resourceRepository, $this->organizationExistenceChecker);
    }

    public function testOrganizationShouldExists(): void
    {
        $this->expectException(OrganizationNotFoundException::class);

        $request = new RegisterResourceRequest('InvalidOrganizationId', 'John', 'Doe');
        $this->service->execute($request);
    }

    public function testResourceShouldExists(): void
    {
        $this->createOrganization('ExistingOrganizationId');

        $request = new RegisterResourceRequest('ExistingOrganizationId', 'John', 'Doe');
        $response = $this->service->execute($request);

        $resource = $this->resourceRepository->findById($response->getId());
        $this->assertNotNull($resource);
        $this->assertSame('ExistingOrganizationId', $resource->getOrganization()->getId());
        $this->assertSame('John', $resource->getFullName()->getFirstName());
        $this->assertSame('DOE', $resource->getFullName()->getLastName());
    }

    private function createOrganization(string $id): void
    {
        $this->organizationExistenceChecker->add($id);
    }
}
