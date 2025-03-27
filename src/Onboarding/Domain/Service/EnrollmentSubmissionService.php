<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Service;

use Candice\Onboarding\Domain\Entity\Applicant;
use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Entity\Organization;
use Candice\Onboarding\Domain\Exception\EnrollmentInPendingApprovalException;
use Candice\Onboarding\Domain\Factory\ApplicantFactory;
use Candice\Onboarding\Domain\Factory\EnrollmentFactory;
use Candice\Onboarding\Domain\Factory\OrganizationFactory;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
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
        if ($existingEnrollment !== null) {
            match ($existingEnrollment->getStatus()) {
                EnrollmentStatus::PENDING_APPROVAL => throw new EnrollmentInPendingApprovalException(),
                // TODO: EnrollmentStatus::APPROVED => throw new EnrollmentAlreadyApprovedException(),
                // TODO: EnrollmentStatus::REJECTED => null,
            };
        }

        return $this->enrollmentFactory->submit($applicant, $organization);
    }
}
