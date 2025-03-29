<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\ApproveEnrollment;

final readonly class ApproveEnrollmentRequest implements ApproveEnrollmentRequestInterface
{
    public function __construct(private string $enrollmentId)
    {
    }

    public function getEnrollmentId(): string
    {
        return $this->enrollmentId;
    }
}
