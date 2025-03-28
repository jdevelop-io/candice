<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Domain\Exception\EnrollmentResubmissionException;
use Candice\Onboarding\Domain\Exception\InvalidApplicantEmailException;
use Candice\Onboarding\Domain\Exception\InvalidApplicantPositionException;
use Candice\Onboarding\Domain\Exception\InvalidSirenChecksumException;
use Candice\Onboarding\Domain\Exception\InvalidSirenFormatException;
use Candice\Onboarding\Domain\Exception\UnsupportedRegistrationNumberTypeException;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;
use Candice\Tests\Unit\Onboarding\Traits\SubmitEnrollmentTestTrait;

final class SubmitEnrollmentServiceTest extends EnrollmentTest
{
    use SubmitEnrollmentTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpSubmitEnrollmentService();
    }

    public function testRegistrationNumberTypeShouldBeSiren(): void
    {
        $this->expectException(UnsupportedRegistrationNumberTypeException::class);

        $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'bn',
            '938123072',
            'Acme Inc.',
        );
    }

    public function testRegistrationNumberShouldContain9Digits(): void
    {
        $this->expectException(InvalidSirenFormatException::class);

        $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '93812307',
            'Acme Inc.',
        );
    }

    public function testRegistrationNumberShouldHaveValidChecksum(): void
    {
        $this->expectException(InvalidSirenChecksumException::class);

        $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '123456789',
            'Acme Inc.',
        );
    }

    public function testEnrollmentInProgressForApplicant(): void
    {
        $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072',
            'Acme Inc.',
        );

        $this->expectException(EnrollmentResubmissionException::class);

        $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072',
            'Acme Inc.',
        );
    }

    public function testApplicantEmailInvalid(): void
    {
        $this->expectException(InvalidApplicantEmailException::class);

        $this->submitEnrollment(
            'paul-henry.dumont',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072',
            'Acme Inc.',
        );
    }

    public function testApplicantPositionInvalid(): void
    {
        $this->expectException(InvalidApplicantPositionException::class);

        $this->submitEnrollment(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive_assistant',
            'siren',
            '938123072',
            'Acme Inc.',
        );
    }

    public function testEnrollmentShouldBeSubmitted(): void
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

        $this->assertEnrollmentSubmitted(
            [
                'applicantEmail' => 'paul-henry.dumont@example.com',
                'applicantFirstName' => 'Paul-Henry',
                'applicantLastName' => 'DUMONT',
                'applicantPosition' => 'executive',
                'organizationRegistrationNumberType' => 'siren',
                'organizationRegistrationNumber' => '938123072',
                'organizationName' => 'Acme Inc.',
            ],
            $response->getEnrollmentId()
        );
    }
}
