<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Repository;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;

final class InMemoryEnrollmentRepository implements EnrollmentRepositoryInterface
{
    /**
     * @var array<string, array<string, Enrollment>>
     */
    private array $enrollmentByRegistrationNumber = [];

    public function insert(Enrollment $enrollment): void
    {
        $registrationNumber = $enrollment->getRegistrationNumber();

        $this->enrollmentByRegistrationNumber[$registrationNumber->getType()][$registrationNumber->getValue()]
            = $enrollment;
    }

    public function findByRegistrationNumber(RegistrationNumber $registrationNumber): ?Enrollment
    {
        if (!isset($this->enrollmentByRegistrationNumber[$registrationNumber->getType()])) {
            return null;
        }

        return $this->enrollmentByRegistrationNumber[$registrationNumber->getType()][$registrationNumber->getValue()];
    }
}
