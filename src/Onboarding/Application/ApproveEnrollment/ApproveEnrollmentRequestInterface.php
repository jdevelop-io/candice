<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\ApproveEnrollment;

interface ApproveEnrollmentRequestInterface
{
    public function getEnrollmentId(): string;
}
