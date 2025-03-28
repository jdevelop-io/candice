<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment;

use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;

final class ApproveEnrollmentServiceTest extends EnrollmentTest
{
    public function testEnrollmentShouldExist(): void
    {
        $this->expectException(EnrollmentNotFoundException::class);

        $this->approveEnrollment('InvalidEnrollmentId');
    }
}
