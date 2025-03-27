<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;

final class InMemoryEnrollmentRepository implements EnrollmentRepositoryInterface
{
    /**
     * @var array<string, Enrollment>
     */
    private array $enrollmentById = [];

    /**
     * @var array<string, array<string, Enrollment>>
     */
    private array $enrollmentByRegistrationNumber = [];

    public function insert(Enrollment $enrollment): void
    {
        $registrationNumber = $enrollment->getOrganization()->getRegistrationNumber();

        $this->enrollmentById[$enrollment->getId()->unwrap()] = $enrollment;
        $this->enrollmentByRegistrationNumber[$registrationNumber->getType()][$registrationNumber->getValue()]
            = $enrollment;
    }

    public function findById(EnrollmentId $id): ?Enrollment
    {
        return $this->enrollmentById[$id->unwrap()] ?? null;
    }

    public function findByOrganizationRegistrationNumber(RegistrationNumber $registrationNumber): ?Enrollment
    {
        if (!isset($this->enrollmentByRegistrationNumber[$registrationNumber->getType()])) {
            return null;
        }

        return $this->enrollmentByRegistrationNumber[$registrationNumber->getType()][$registrationNumber->getValue()];
    }
}
