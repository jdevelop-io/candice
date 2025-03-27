<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Domain\Factory\ApplicantFactory;
use Candice\Onboarding\Domain\Factory\EnrollmentFactory;
use Candice\Onboarding\Domain\Factory\OrganizationFactory;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Infrastructure\IdGenerator\IncrementalEnrollmentIdGenerator;

trait SetupFactoriesTestTrait
{
    private RegistrationNumberFactory $registrationNumberFactory;
    private ApplicantFactory $applicantFactory;
    private OrganizationFactory $organizationFactory;
    private EnrollmentFactory $enrollmentFactory;

    public function setUpFactories(): void
    {
        $this->registrationNumberFactory = new RegistrationNumberFactory();
        $this->applicantFactory = new ApplicantFactory();
        $this->organizationFactory = new OrganizationFactory($this->registrationNumberFactory);
        $this->enrollmentFactory = new EnrollmentFactory(
            new IncrementalEnrollmentIdGenerator(),
        );
    }
}
