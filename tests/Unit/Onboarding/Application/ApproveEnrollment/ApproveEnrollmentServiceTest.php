<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment;

use Candice\Onboarding\Domain\Exception\EnrollmentAlreadyProcessedException;
use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;

final class ApproveEnrollmentServiceTest extends EnrollmentTest
{
    public function testEnrollmentShouldExist(): void
    {
        $this->expectException(EnrollmentNotFoundException::class);

        $this->approveEnrollment('InvalidEnrollmentId');
    }

    public function testEnrollmentShouldNotAlreadyBeApproved(): void
    {
        $response = $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072',
            'Acme Inc.',
        );

        $response = $this->approveEnrollment($response->getEnrollmentId());

        $this->expectException(EnrollmentAlreadyProcessedException::class);

        $this->approveEnrollment($response->getEnrollmentId());
    }

    public function testEnrollmentShouldBeApproved(): void
    {
        $response = $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072',
            'Acme Inc.',
        );

        $response = $this->approveEnrollment($response->getEnrollmentId());

        $this->assertEnrollmentApproved($response->getEnrollmentId());
    }
}
