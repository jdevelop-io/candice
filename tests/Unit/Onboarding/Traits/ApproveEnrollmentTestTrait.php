<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentService;
use Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentRequest;

trait ApproveEnrollmentTestTrait
{
    private ApproveEnrollmentService $approveEnrollmentService;

    protected function setUpApproveEnrollmentService(): void
    {
        $this->approveEnrollmentService = new ApproveEnrollmentService();
    }

    protected function approveEnrollment(string $enrollmentId): void
    {
        $this->approveEnrollmentService->execute(new ApproveEnrollmentRequest($enrollmentId));
    }
}
