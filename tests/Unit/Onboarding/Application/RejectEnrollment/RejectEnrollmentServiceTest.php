<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\RejectEnrollment;

use Candice\Onboarding\Domain\Exception\EnrollmentAlreadyProcessedException;
use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;
use Candice\Tests\Unit\Onboarding\Traits\RejectEnrollmentTestTrait;

final class RejectEnrollmentServiceTest extends EnrollmentTest
{
    use RejectEnrollmentTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRejectEnrollmentService();
        $this->defineAdministrator('AdministratorId', 'John', 'Doe');
    }

    public function testEnrollmentShouldExist(): void
    {
        $this->expectException(EnrollmentNotFoundException::class);

        $this->rejectEnrollment('InvalidEnrollmentId');
    }

    public function testEnrollmentShouldNotAlreadyBeRejected(): void
    {
        $enrollment = $this->createEnrollment(enrollmentStatus: 'rejected');

        $this->expectException(EnrollmentAlreadyProcessedException::class);

        $this->rejectEnrollment($enrollment->getId()->unwrap());
    }

    public function testEnrollmentShouldBeRejected(): void
    {
        $enrollment = $this->createEnrollment();

        $response = $this->rejectEnrollment($enrollment->getId()->unwrap());

        $this->assertEnrollmentRejected(
            [
                'rejectedById' => 'AdministratorId',
                'rejectedByFirstName' => 'John',
                'rejectedByLastName' => 'DOE',
                'rejectedAt' => $this->clock->format(),
            ],
            $response->getEnrollmentId()
        );
    }
}
