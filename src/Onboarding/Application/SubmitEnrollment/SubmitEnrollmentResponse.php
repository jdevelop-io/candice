<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\SubmitEnrollment;

final readonly class SubmitEnrollmentResponse
{
    public function __construct(
        private string $enrollmentId,
    ) {
    }

    public function getEnrollmentId(): string
    {
        return $this->enrollmentId;
    }
}
