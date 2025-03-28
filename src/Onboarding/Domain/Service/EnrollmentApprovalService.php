<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Service;

use Candice\Onboarding\Domain\Entity\Enrollment;

final readonly class EnrollmentApprovalService
{
    public function approve(Enrollment $enrollment): void
    {
        $enrollment->approve();
    }
}
