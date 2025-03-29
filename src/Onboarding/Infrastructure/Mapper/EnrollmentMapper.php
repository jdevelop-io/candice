<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Mapper;

use Candice\Onboarding\Domain\Entity\Applicant;
use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Entity\Organization;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Domain\ValueObject\ApplicantEmail;
use Candice\Onboarding\Domain\ValueObject\ApplicantFullName;
use Candice\Onboarding\Domain\ValueObject\ApplicantPosition;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Onboarding\Domain\ValueObject\OrganizationName;

final readonly class EnrollmentMapper
{
    public function __construct(private RegistrationNumberFactory $registrationNumberFactory)
    {
    }

    /**
     * @param array{
     *     id: string,
     *     email: string,
     *     first_name: string,
     *     last_name: string,
     *     position: string,
     *     registration_number: string,
     *     registration_number_type: string,
     *     name: string,
     *     status: string,
     * } $data
     */
    public function mapToDomain(array $data): Enrollment
    {
        return new Enrollment(
            new EnrollmentId($data['id']),
            new Applicant(
                new ApplicantEmail($data['email']),
                new ApplicantFullName($data['first_name'], $data['last_name']),
                ApplicantPosition::fromValue($data['position'])
            ),
            new Organization(
                $this->registrationNumberFactory->create($data['registration_number_type'], $data['registration_number']),
                new OrganizationName($data['name'])
            ),
            EnrollmentStatus::fromValue($data['status']),
            // TODO: processedBy: null,
            // TODO: processedAt: null,
        );
    }
}
