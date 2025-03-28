<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Service;

use Candice\Onboarding\Domain\Entity\Applicant;
use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Entity\Organization;
use Candice\Onboarding\Domain\Exception\EnrollmentResubmissionException;
use Candice\Onboarding\Domain\Factory\ApplicantFactory;
use Candice\Onboarding\Domain\Factory\EnrollmentFactory;
use Candice\Onboarding\Domain\Factory\OrganizationFactory;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;

final readonly class EnrollmentSubmissionService
{
    public function __construct(
        private RegistrationNumberFactory $registrationNumberFactory,
        private ApplicantFactory $applicantFactory,
        private OrganizationFactory $organizationFactory,
        private EnrollmentFactory $enrollmentFactory,
    ) {
    }

    public function createRegistrationNumber(
        string $getOrganizationRegistrationNumberType,
        string $getOrganizationRegistrationNumber
    ): RegistrationNumber {
        return $this->registrationNumberFactory->create(
            $getOrganizationRegistrationNumberType,
            $getOrganizationRegistrationNumber
        );
    }

    public function createApplicant(
        string $applicantEmail,
        string $applicantFirstName,
        string $applicantLastName,
        string $applicantPosition
    ): Applicant {
        return $this->applicantFactory->create(
            $applicantEmail,
            $applicantFirstName,
            $applicantLastName,
            $applicantPosition
        );
    }

    public function createOrganization(
        string $organizationRegistrationNumberType,
        string $organizationRegistrationNumber,
        string $organizationName
    ): Organization {
        return $this->organizationFactory->create(
            $organizationRegistrationNumberType,
            $organizationRegistrationNumber,
            $organizationName
        );
    }

    public function submit(
        ?Enrollment $existingEnrollment,
        Applicant $applicant,
        Organization $organization
    ): Enrollment {
        $this->guardAgainstResubmission($existingEnrollment);

        return $this->enrollmentFactory->submit($applicant, $organization);
    }

    private function guardAgainstResubmission(?Enrollment $existingEnrollment): void
    {
        if ($existingEnrollment === null) {
            return;
        }

        throw new EnrollmentResubmissionException(
            $existingEnrollment->getOrganization()->getName()->unwrap(),
            $existingEnrollment->getStatus(),
        );
    }
}
