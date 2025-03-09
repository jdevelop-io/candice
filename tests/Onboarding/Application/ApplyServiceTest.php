<?php

declare(strict_types=1);

namespace Candice\Tests\Onboarding\Application;

use Candice\Onboarding\Application\Apply\ApplyRequest;
use Candice\Onboarding\Application\Apply\ApplyService;
use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Enum\ApplicationStatus;
use Candice\Onboarding\Domain\Exception\ApplicationInPendingValidationException;
use Candice\Onboarding\Domain\Exception\InvalidOrganizationRegistrationNumberException;
use Candice\Onboarding\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Onboarding\Domain\Exception\UserAlreadyRegisteredException;
use Candice\Onboarding\Infrastructure\Repository\InMemoryApplicationRepository;
use Candice\Onboarding\Infrastructure\Service\InMemoryOrganizationService;
use Candice\Onboarding\Infrastructure\Service\InMemoryUserService;
use PHPUnit\Framework\TestCase;

final class ApplyServiceTest extends TestCase
{
    private readonly InMemoryUserService $userExistenceChecker;
    private readonly InMemoryOrganizationService $organizationExistenceChecker;
    private readonly InMemoryApplicationRepository $applicationRepository;
    private readonly ApplyService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userExistenceChecker = new InMemoryUserService();
        $this->organizationExistenceChecker = new InMemoryOrganizationService();
        $this->applicationRepository = new InMemoryApplicationRepository();
        $this->service = new ApplyService(
            $this->userExistenceChecker,
            $this->organizationExistenceChecker,
            $this->applicationRepository
        );
    }

    public function testUserIsUniqueByEmail(): void
    {
        $userEmail = 'john.doe@example.com';
        $this->createUser($userEmail);

        $this->expectException(UserAlreadyRegisteredException::class);

        $this->service->execute(new ApplyRequest($userEmail, '123456789', 'Organization Name'));
    }

    public function testOrganizationRegistrationNumberShouldBeValid(): void
    {
        $userEmail = 'john.doe@example.com';

        $this->expectException(InvalidOrganizationRegistrationNumberException::class);

        $this->service->execute(new ApplyRequest($userEmail, 'InvalidRegistrationNumber', 'Organization Name'));
    }

    public function testOrganizationIsUniqueByRegistrationNumber(): void
    {
        $userEmail = 'john.doe@example.com';
        $this->createOrganization('123456789');

        $this->expectException(OrganizationAlreadyRegisteredException::class);

        $this->service->execute(new ApplyRequest($userEmail, '123456789', 'Organization Name'));
    }

    public function testOnlyOneApplicationForOrganization(): void
    {
        $userEmail = 'john.doe@example.com';
        $this->createApplication($userEmail, '123456789', 'Organization Name');

        $this->expectException(ApplicationInPendingValidationException::class);

        $this->service->execute(new ApplyRequest($userEmail, '123456789', 'Organization Name'));
    }

    public function testApplicationIsCreated(): void
    {
        $userEmail = 'john.doe@example.com';
        $organizationRegistrationNumber = '123456789';
        $organizationName = 'Organization Name';

        $response = $this->service->execute(
            new ApplyRequest($userEmail, $organizationRegistrationNumber, $organizationName)
        );

        $application = $this->applicationRepository->findById($response->getApplicationId());
        $this->assertSame($userEmail, $application->getUserEmail());
        $this->assertSame($organizationRegistrationNumber, $application->getOrganizationRegistrationNumber());
        $this->assertSame($organizationName, $application->getOrganizationName());
        $this->assertSame(ApplicationStatus::PENDING_APPROVAL, $application->getStatus());
    }

    private function createUser(string $email): void
    {
        $this->userExistenceChecker->add($email);
    }

    private function createOrganization(string $registrationNumber): void
    {
        $this->organizationExistenceChecker->add($registrationNumber);
    }

    private function createApplication(
        string $userEmail,
        string $organizationRegistrationNumber,
        string $organizationName
    ): void {
        $application = Application::apply(
            $userEmail,
            $organizationRegistrationNumber,
            $organizationName
        );

        $this->applicationRepository->save($application);
    }
}
