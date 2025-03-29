<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;
use Candice\Onboarding\Infrastructure\Mapper\EnrollmentMapper;
use Doctrine\DBAL\Connection;
use Safe\DateTimeImmutable;

final readonly class DoctrineEnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private EnrollmentMapper $enrollmentMapper,
    ) {
    }

    public function insert(Enrollment $enrollment): void
    {
        $this->connection->transactional(function () use ($enrollment) {
            $createdAt = (new DateTimeImmutable())->format('Y-m-d H:i:s');

            $this->connection->insert('onboarding_applicants', [
                'email' => $enrollment->getApplicant()->getEmail()->unwrap(),
                'first_name' => $enrollment->getApplicant()->getFullName()->getFirstName(),
                'last_name' => $enrollment->getApplicant()->getFullName()->getLastName(),
                'position' => $enrollment->getApplicant()->getPosition()->unwrap(),
                'created_at' => $createdAt,
            ]);

            $this->connection->insert('onboarding_organizations', [
                'registration_number' => $enrollment->getOrganization()->getRegistrationNumber()->getValue(),
                'registration_number_type' => $enrollment->getOrganization()->getRegistrationNumber()->getType(),
                'name' => $enrollment->getOrganization()->getName()->unwrap(),
                'created_at' => $createdAt,
            ]);

            $this->connection->insert('onboarding_enrollments', [
                'id' => $enrollment->getId()->unwrap(),
                'applicant_id' => $enrollment->getApplicant()->getEmail()->unwrap(),
                'organization_id' => $enrollment->getOrganization()->getRegistrationNumber()->getValue(),
                'status' => $enrollment->getStatus()->unwrap(),
                'created_at' => $createdAt,
            ]);
        });
    }

    public function update(Enrollment $enrollment): void
    {
        $this->connection->transactional(function () use ($enrollment) {
            $this->connection->update('onboarding_enrollments', [
                'status' => $enrollment->getStatus()->unwrap(),
                'processed_by' => $enrollment->getProcessedBy()?->getId()->unwrap(),
                'processed_at' => $enrollment->getProcessedAt()?->format('Y-m-d H:i:s'),
            ], [
                'id' => $enrollment->getId()->unwrap(),
            ]);
        });
    }

    public function findById(EnrollmentId $id): ?Enrollment
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'enrollment.id, enrollment.status, applicant.email, applicant.first_name, applicant.last_name,
                applicant.position, organization.registration_number, organization.registration_number_type,
                organization.name'
            )
            ->from('onboarding_enrollments', 'enrollment')
            ->innerJoin('enrollment', 'onboarding_applicants', 'applicant', 'enrollment.applicant_id = applicant.email')
            ->innerJoin(
                'enrollment',
                'onboarding_organizations',
                'organization',
                'enrollment.organization_id = organization.registration_number'
            )
            ->where('enrollment.id = :id')
            ->setParameter('id', $id->unwrap())
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         *     email: string,
         *     first_name: string,
         *     last_name: string,
         *     position: string,
         *     registration_number: string,
         *     registration_number_type: string,
         *     name: string,
         *     status: string,
         * }|false $data
         */
        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return $this->enrollmentMapper->mapToDomain($data);
    }

    public function findByOrganizationRegistrationNumber(RegistrationNumber $registrationNumber): ?Enrollment
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'enrollment.id, enrollment.status, applicant.email, applicant.first_name, applicant.last_name,
                applicant.position, organization.registration_number, organization.registration_number_type,
                organization.name'
            )
            ->from('onboarding_enrollments', 'enrollment')
            ->innerJoin('enrollment', 'onboarding_applicants', 'applicant', 'enrollment.applicant_id = applicant.email')
            ->innerJoin(
                'enrollment',
                'onboarding_organizations',
                'organization',
                'enrollment.organization_id = organization.registration_number'
            )
            ->where('organization.registration_number = :registrationNumber')
            ->setParameter('registrationNumber', $registrationNumber->getValue())
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         *     email: string,
         *     first_name: string,
         *     last_name: string,
         *     position: string,
         *     registration_number: string,
         *     registration_number_type: string,
         *     name: string,
         *     status: string,
         * }|false $data
         */
        $data = $result->fetchAssociative();

        if ($data === false) {
            return null;
        }

        return $this->enrollmentMapper->mapToDomain($data);
    }
}
