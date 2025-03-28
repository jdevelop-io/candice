<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\RejectEnrollment;

interface RejectEnrollmentRequestInterface
{
    public function getEnrollmentId(): string;
}
