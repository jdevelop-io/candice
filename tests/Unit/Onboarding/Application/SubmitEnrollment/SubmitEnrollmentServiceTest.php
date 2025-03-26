<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentService;
use Candice\Onboarding\Domain\Exception\EnrollmentInPendingApprovalException;
use Candice\Onboarding\Domain\Exception\InvalidApplicantEmailException;
use Candice\Onboarding\Domain\Exception\InvalidApplicantPositionException;
use Candice\Onboarding\Domain\Exception\InvalidSirenChecksumException;
use Candice\Onboarding\Domain\Exception\InvalidSirenFormatException;
use Candice\Onboarding\Domain\Exception\UnsupportedRegistrationNumberTypeException;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;

final class SubmitEnrollmentServiceTest extends EnrollmentTest
{
    private SubmitEnrollmentService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new SubmitEnrollmentService(
            $this->enrollmentRepository,
            $this->enrollmentIdGenerator,
            $this->registrationNumberFactory,
        );
    }

    public function testRegistrationNumberTypeShouldBeSiren(): void
    {
        $this->expectException(UnsupportedRegistrationNumberTypeException::class);

        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'bn',
            '938123072'
        );
        $this->service->execute($request);
    }

    public function testRegistrationNumberShouldContain9Digits(): void
    {
        $this->expectException(InvalidSirenFormatException::class);

        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '93812307'
        );
        $this->service->execute($request);
    }

    public function testRegistrationNumberShouldHaveValidChecksum(): void
    {
        $this->expectException(InvalidSirenChecksumException::class);

        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '123456789'
        );
        $this->service->execute($request);
    }

    public function testEnrollmentInProgressForApplicant(): void
    {
        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072'
        );
        $this->service->execute($request);

        $this->expectException(EnrollmentInPendingApprovalException::class);

        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072'
        );
        $this->service->execute($request);
    }

    public function testApplicantEmailInvalid(): void
    {
        $this->expectException(InvalidApplicantEmailException::class);

        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072'
        );
        $this->service->execute($request);
    }

    public function testApplicantPositionInvalid(): void
    {
        $this->expectException(InvalidApplicantPositionException::class);

        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive_assistant',
            'siren',
            '938123072'
        );
        $this->service->execute($request);
    }

    public function testEnrollmentShouldBeSubmitted(): void
    {
        $request = new SubmitEnrollmentRequest(
            'paul-henry.dumont@example.com',
            'paul-henry',
            'dumont',
            'executive',
            'siren',
            '938123072'
        );

        $response = $this->service->execute($request);

        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($response->getEnrollmentId()));
        $this->assertNotNull($enrollment);
        $this->assertSame('paul-henry.dumont@example.com', $enrollment->getApplicant()->getEmail()->unwrap());
        $this->assertSame('Paul-Henry', $enrollment->getApplicant()->getFullName()->getFirstName());
        $this->assertSame('DUMONT', $enrollment->getApplicant()->getFullName()->getLastName());
        $this->assertSame('executive', $enrollment->getApplicant()->getPosition()->unwrap());
        $this->assertSame('siren', $enrollment->getRegistrationNumber()->getType());
        $this->assertSame('938123072', $enrollment->getRegistrationNumber()->getValue());
    }
}
