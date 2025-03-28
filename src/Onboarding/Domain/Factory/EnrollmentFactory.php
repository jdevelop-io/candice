<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Factory;

use Candice\Onboarding\Domain\Entity\Applicant;
use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Entity\Organization;
use Candice\Onboarding\Domain\IdGenerator\EnrollmentIdGeneratorInterface;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;

final readonly class EnrollmentFactory
{
    private EnrollmentIdGeneratorInterface $enrollmentIdGenerator;

    public function __construct(EnrollmentIdGeneratorInterface $enrollmentIdGenerator)
    {
        $this->enrollmentIdGenerator = $enrollmentIdGenerator;
    }

    public function submit(
        Applicant $applicant,
        Organization $organization
    ): Enrollment {
        $id = $this->enrollmentIdGenerator->generate();

        return Enrollment::submit($id, $applicant, $organization);
    }

    public function create(
        Applicant $applicant,
        Organization $organization,
        EnrollmentStatus $status
    ): Enrollment {
        $id = $this->enrollmentIdGenerator->generate();

        return new Enrollment($id, $applicant, $organization, $status);
    }
}
