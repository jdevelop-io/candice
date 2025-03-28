<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Tests\Unit\Onboarding\Traits\SetupFactoriesTestTrait;
use Candice\Tests\Unit\Onboarding\Traits\SetupRepositoriesTestTrait;
use Candice\Tests\Unit\Shared\Traits\SetupEventBusTestTrait;
use PHPUnit\Framework\TestCase;

abstract class EnrollmentTest extends TestCase
{
    use SetupEventBusTestTrait;
    use SetupFactoriesTestTrait;
    use SetupRepositoriesTestTrait;

    protected function setUp(): void
    {
        $this->setUpFactories();
        $this->setUpRepositories();
        $this->setUpEventBus();
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
