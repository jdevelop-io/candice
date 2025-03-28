<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentResponse;
use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentService;
use Candice\Onboarding\Domain\Service\EnrollmentApprovalService;
use Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentRequest;

trait ApproveEnrollmentTestTrait
{
    private EnrollmentApprovalService $enrollmentApprovalService;
    private ApproveEnrollmentService $approveEnrollmentService;

    protected function setUpApproveEnrollmentService(): void
    {
        $this->enrollmentApprovalService = new EnrollmentApprovalService();
        $this->approveEnrollmentService = new ApproveEnrollmentService(
            $this->enrollmentRepository,
            $this->enrollmentApprovalService
        );
    }

    protected function approveEnrollment(string $enrollmentId): ApproveEnrollmentResponse
    {
        return $this->approveEnrollmentService->execute(new ApproveEnrollmentRequest($enrollmentId));
    }
}
