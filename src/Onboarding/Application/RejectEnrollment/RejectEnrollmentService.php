<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\RejectEnrollment;

final readonly class RejectEnrollmentService
{
    public function execute(RejectEnrollmentRequestInterface $request): RejectEnrollmentResponse
    {
        return new RejectEnrollmentResponse();
    }
}
