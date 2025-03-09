<?php

declare(strict_types=1);

namespace Candice\Tests\Onboarding\Application;

use Candice\Onboarding\Application\List\ListRequest;
use Candice\Onboarding\Application\List\ListService;
use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Infrastructure\Repository\InMemoryApplicationRepository;
use PHPUnit\Framework\TestCase;

final class ListServiceTest extends TestCase
{
    private readonly InMemoryApplicationRepository $applicationRepository;
    private readonly ListService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->applicationRepository = new InMemoryApplicationRepository();
        $this->service = new ListService($this->applicationRepository);
    }

    public function testEmptyList(): void
    {
        $response = $this->service->execute(new ListRequest());

        $this->assertEmpty($response->getApplications());
    }

    public function testOnePendingApproval(): void
    {
        $expected = $this->createPendingApprovalApplication('john.doe@example.com', '123456789', 'Acme Inc.');

        $response = $this->service->execute(new ListRequest());

        $this->assertCount(1, $response->getApplications());
        $application = $response->getApplications()[0];
        $this->assertSame($expected->getId(), $application->getId());
        $this->assertSame($expected->getUserEmail(), $application->getUserEmail());
        $this->assertSame(
            $expected->getOrganizationRegistrationNumber(),
            $application->getOrganizationRegistrationNumber()
        );
        $this->assertSame($expected->getOrganizationName(), $application->getOrganizationName());
        $this->assertSame($expected->getStatus()->value, $application->getStatus());
    }

    public function testOnePendingApprovalAndOneApproved(): void
    {
        $this->createPendingApprovalApplication('jane.doe@example.com', '987654321', 'Dummy Corp.');
        $this->createApprovedApplication('jane.doe@example.com', '987654321', 'Dummy Corp.');

        $response = $this->service->execute(new ListRequest());

        $this->assertCount(2, $response->getApplications());
    }

    public function testFilterByStatus(): void
    {
        $this->createPendingApprovalApplication('john.doe@example.com', '123456789', 'Acme Inc.');
        $this->createApprovedApplication('jane.doe@example.com', '987654321', 'Dummy Corp.');

        $response = $this->service->execute(new ListRequest('approved'));

        $this->assertCount(1, $response->getApplications());
        $application = $response->getApplications()[0];
        $this->assertSame('approved', $application->getStatus());
    }

    private function createPendingApprovalApplication(
        string $userEmail,
        string $organizationRegistrationNumber,
        string $organizationName
    ): Application {
        $application = Application::apply(
            $userEmail,
            $organizationRegistrationNumber,
            $organizationName,
        );

        $this->applicationRepository->save($application);

        return $application;
    }

    private function createApprovedApplication(
        string $userEmail,
        string $organizationRegistrationNumber,
        string $organizationName
    ): Application {
        $application = Application::apply(
            $userEmail,
            $organizationRegistrationNumber,
            $organizationName,
        );

        $application->approve();

        $this->applicationRepository->save($application);

        return $application;
    }
}
