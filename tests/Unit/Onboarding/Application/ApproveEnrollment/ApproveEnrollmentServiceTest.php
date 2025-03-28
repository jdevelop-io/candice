<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment;

use Candice\Onboarding\Domain\Exception\EnrollmentAlreadyProcessedException;
use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;
use Candice\Tests\Unit\Onboarding\Traits\ApproveEnrollmentTestTrait;

final class ApproveEnrollmentServiceTest extends EnrollmentTest
{
    use ApproveEnrollmentTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpApproveEnrollmentService();
        $this->defineAdministrator('AdministratorId', 'John', 'Doe');
    }

    public function testEnrollmentShouldExist(): void
    {
        $this->expectException(EnrollmentNotFoundException::class);

        $this->approveEnrollment('InvalidEnrollmentId');
    }

    public function testEnrollmentShouldNotAlreadyBeApproved(): void
    {
        $enrollment = $this->createEnrollment(enrollmentStatus: 'approved');

        $this->expectException(EnrollmentAlreadyProcessedException::class);

        $this->approveEnrollment($enrollment->getId()->unwrap());
    }

    public function testEnrollmentShouldBeApproved(): void
    {
        $enrollment = $this->createEnrollment();

        $response = $this->approveEnrollment($enrollment->getId()->unwrap());

        $this->assertEnrollmentApproved(
            [
                'approvedById' => 'AdministratorId',
                'approvedByFirstName' => 'John',
                'approvedByLastName' => 'DOE',
                'approvedAt' => $this->clock->format(),
            ],
            $response->getEnrollmentId()
        );
    }
}
