<?php

declare(strict_types=1);

namespace Candice\Tests\Onboarding\Application;

use Candice\Onboarding\Application\Approve\ApproveRequest;
use Candice\Onboarding\Application\Approve\ApproveService;
use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Exception\ApplicationNotFoundException;
use Candice\Onboarding\Domain\Exception\ApplicationNotPendingApprovalException;
use Candice\Onboarding\Infrastructure\Repository\InMemoryApplicationRepository;
use Candice\Onboarding\Infrastructure\Symfony\MessagePublisher\NullApplicationApprovedEventPublisher;
use PHPUnit\Framework\TestCase;

final class ApproveServiceTest extends TestCase
{
    private readonly InMemoryApplicationRepository $applicationRepository;
    private readonly ApproveService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->applicationRepository = new InMemoryApplicationRepository();
        $this->service = new ApproveService(new NullApplicationApprovedEventPublisher(), $this->applicationRepository);
    }

    public function testApplicationDoesNotExists(): void
    {
        $this->expectException(ApplicationNotFoundException::class);

        $this->service->execute(new ApproveRequest('InvalidApplicationId'));
    }

    public function testApplicationAlreadyApproved(): void
    {
        $application = $this->createApprovedApplication();

        $this->expectException(ApplicationNotPendingApprovalException::class);

        $this->service->execute(new ApproveRequest($application->getId()));
    }

    public function testApplicationShouldBeApproved(): void
    {
        $application = $this->createApplication();

        $response = $this->service->execute(new ApproveRequest($application->getId()));

        $this->assertSame($application->getId(), $response->getId());
        $this->assertSame('approved', $response->getStatus());
    }

    private function createApplication(): Application
    {
        $application = Application::apply(
            'john.doe@example.com',
            '123456789',
            'Acme Inc.',
        );

        $this->applicationRepository->save($application);

        return $application;
    }

    private function createApprovedApplication(): Application
    {
        $application = Application::apply(
            'john.doe@example.com',
            '123456789',
            'Acme Inc.',
        );

        $application->approve();

        $this->applicationRepository->save($application);

        return $application;
    }
}
