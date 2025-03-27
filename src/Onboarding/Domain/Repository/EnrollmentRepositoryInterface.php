<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Repository;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\RegistrationNumber;

interface EnrollmentRepositoryInterface
{
    public function insert(Enrollment $enrollment): void;

    public function findById(EnrollmentId $id): ?Enrollment;

    public function findByOrganizationRegistrationNumber(RegistrationNumber $registrationNumber): ?Enrollment;
}
