<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\ApproveEnrollment;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;

final readonly class ApproveEnrollmentService
{
    public function __construct()
    {
    }

    public function execute(ApproveEnrollmentRequestInterface $request): ApproveEnrollmentResponse
    {
        $this->getEnrollment($request->getEnrollmentId());
    }

    private function getEnrollment(string $enrollmentId): Enrollment
    {
        return throw new EnrollmentNotFoundException($enrollmentId);
    }
}
