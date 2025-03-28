<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding;

use Candice\Onboarding\Domain\Entity\Administrator;
use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\ValueObject\AdministratorFullName;
use Candice\Onboarding\Domain\ValueObject\AdministratorId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Onboarding\Infrastructure\Provider\InMemoryAdministratorProvider;
use Candice\Shared\Infrastructure\Clock\FrozenClock;
use Candice\Tests\Unit\Onboarding\Traits\SetupFactoriesTestTrait;
use Candice\Tests\Unit\Onboarding\Traits\SetupRepositoriesTestTrait;
use Candice\Tests\Unit\Shared\Traits\SetupEventBusTestTrait;
use PHPUnit\Framework\TestCase;

abstract class EnrollmentTest extends TestCase
{
    use SetupEventBusTestTrait;
    use SetupFactoriesTestTrait;
    use SetupRepositoriesTestTrait;

    protected FrozenClock $clock;
    protected InMemoryAdministratorProvider $administratorProvider;

    protected function setUp(): void
    {
        $this->clock = new FrozenClock('2025-03-28 13:56:11');
        $this->administratorProvider = new InMemoryAdministratorProvider();

        $this->setUpFactories();
        $this->setUpRepositories();
        $this->setUpEventBus();
    }

    protected function defineAdministrator(
        string $administratorId,
        string $administratorFirstName,
        string $administratorLastName
    ): void {
        $this->administratorProvider->define(
            new Administrator(
                new AdministratorId($administratorId),
                new AdministratorFullName($administratorFirstName, $administratorLastName)
            )
        );
    }

    protected function createEnrollment(
        ?string $applicantEmail = null,
        ?string $applicantFirstName = null,
        ?string $applicantLastName = null,
        ?string $applicantPosition = null,
        ?string $organizationRegistrationNumberType = null,
        ?string $organizationRegistrationNumber = null,
        ?string $organizationName = null,
        ?string $enrollmentStatus = null
    ): Enrollment {
        $applicant = $this->applicantFactory->create(
            $applicantEmail ?? 'paul-henry.dumont@example.com',
            $applicantFirstName ?? 'paul-henry',
            $applicantLastName ?? 'dumont',
            $applicantPosition ?? 'executive'
        );

        $organization = $this->organizationFactory->create(
            $organizationRegistrationNumberType ?? 'siren',
            $organizationRegistrationNumber ?? '938123072',
            $organizationName ?? 'Acme Inc.'
        );

        $enrollmentStatus = $enrollmentStatus
            ? EnrollmentStatus::fromValue($enrollmentStatus)
            : EnrollmentStatus::PENDING_APPROVAL;

        $enrollment = $this->enrollmentFactory->create($applicant, $organization, $enrollmentStatus);

        $this->enrollmentRepository->insert($enrollment);

        return $enrollment;
    }
}
