<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\RejectEnrollment;

final readonly class RejectEnrollmentResponse
{
    public function __construct(private string $enrollmentId)
    {
    }

    public function getEnrollmentId(): string
    {
        return $this->enrollmentId;
    }
}
