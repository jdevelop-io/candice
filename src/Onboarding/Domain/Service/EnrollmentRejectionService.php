<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Service;

use Candice\Onboarding\Domain\Entity\Administrator;
use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\ValueObject\EnrollmentProcessingDateTime;
use Candice\Shared\Domain\Clock\ClockInterface;

final readonly class EnrollmentRejectionService
{
    public function __construct(private ClockInterface $clock)
    {
    }

    public function reject(
        Enrollment $enrollment,
        Administrator $rejectedBy,
    ): void {
        $rejectedAt = new EnrollmentProcessingDateTime($this->clock->now());

        $enrollment->reject($rejectedBy, $rejectedAt);
    }
}
