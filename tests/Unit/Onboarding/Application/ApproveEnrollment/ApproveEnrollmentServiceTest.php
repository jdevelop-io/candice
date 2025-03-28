<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment;

use Candice\Onboarding\Domain\Exception\EnrollmentAlreadyProcessedException;
use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;
use Candice\Tests\Unit\Onboarding\Traits\ApproveEnrollmentTestTrait;

final class ApproveEnrollmentServiceTest extends EnrollmentTest
{
    use ApproveEnrollmentTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpApproveEnrollmentService();
    }

    public function testEnrollmentShouldExist(): void
    {
        $this->expectException(EnrollmentNotFoundException::class);

        $this->approveEnrollment('InvalidEnrollmentId');
    }

    public function testEnrollmentShouldNotAlreadyBeApproved(): void
    {
        $enrollment = $this->createEnrollment(enrollmentStatus: EnrollmentStatus::APPROVED->unwrap());

        $this->expectException(EnrollmentAlreadyProcessedException::class);

        $this->approveEnrollment($enrollment->getId()->unwrap());
    }

    public function testEnrollmentShouldBeApproved(): void
    {
        $enrollment = $this->createEnrollment();

        $response = $this->approveEnrollment($enrollment->getId()->unwrap());

        $this->assertEnrollmentApproved($response->getEnrollmentId());
    }
}
