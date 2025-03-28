<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\RejectEnrollment\RejectEnrollmentResponse;
use Candice\Onboarding\Application\RejectEnrollment\RejectEnrollmentService;
use Candice\Tests\Unit\Onboarding\Application\RejectEnrollment\RejectEnrollmentRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait RejectEnrollmentTestTrait
{
    use EventBusTestTrait;

    private RejectEnrollmentService $service;

    protected function setUpRejectEnrollmentService(): void
    {
        $this->service = new RejectEnrollmentService();
    }

    protected function rejectEnrollment(string $enrollmentId): RejectEnrollmentResponse
    {
        return $this->service->execute(new RejectEnrollmentRequest($enrollmentId));
    }
}
