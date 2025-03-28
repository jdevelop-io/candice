<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment;

use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentRequestInterface;

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
