<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\RejectEnrollment;

use Candice\Onboarding\Application\RejectEnrollment\RejectEnrollmentRequestInterface;

final readonly class RejectEnrollmentRequest implements RejectEnrollmentRequestInterface
{
    public function __construct(private string $enrollmentId)
    {
    }

    public function getEnrollmentId(): string
    {
        return $this->enrollmentId;
    }
}
