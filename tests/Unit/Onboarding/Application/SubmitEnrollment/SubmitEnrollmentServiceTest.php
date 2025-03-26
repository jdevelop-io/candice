<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentService;
use Candice\Onboarding\Domain\Exception\EnrollmentInPendingApprovalException;
use Candice\Onboarding\Domain\Exception\InvalidSirenChecksumException;
use Candice\Onboarding\Domain\Exception\InvalidSirenFormatException;
use Candice\Onboarding\Domain\Exception\UnsupportedRegistrationNumberTypeException;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Infrastructure\Repository\InMemoryEnrollmentRepository;
use Candice\Tests\Unit\Onboarding\EnrollmentTest;

final class SubmitEnrollmentServiceTest extends EnrollmentTest
{
    private SubmitEnrollmentService $service;

    protected function setUp(): void
    {
        $this->service = new SubmitEnrollmentService(
            new InMemoryEnrollmentRepository(), new RegistrationNumberFactory()
        );
    }

    public function testRegistrationNumberTypeShouldBeSiren(): void
    {
        $this->expectException(UnsupportedRegistrationNumberTypeException::class);

        $request = new SubmitEnrollmentRequest('bn', '938123072');
        $this->service->execute($request);
    }

    public function testRegistrationNumberShouldContain9Digits(): void
    {
        $this->expectException(InvalidSirenFormatException::class);

        $request = new SubmitEnrollmentRequest('siren', '93812307');
        $this->service->execute($request);
    }

    public function testRegistrationNumberShouldHaveValidChecksum(): void
    {
        $this->expectException(InvalidSirenChecksumException::class);

        $request = new SubmitEnrollmentRequest('siren', '123456789');
        $this->service->execute($request);
    }

    public function testEnrollmentInProgressForApplicant(): void
    {
        $request = new SubmitEnrollmentRequest('siren', '938123072');
        $this->service->execute($request);

        $this->expectException(EnrollmentInPendingApprovalException::class);

        $request = new SubmitEnrollmentRequest('siren', '938123072');
        $this->service->execute($request);
    }
}
