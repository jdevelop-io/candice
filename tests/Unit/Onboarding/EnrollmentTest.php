<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentResponse;
use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentService;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Infrastructure\IdGenerator\IncrementalEnrollmentIdGenerator;
use Candice\Onboarding\Infrastructure\Repository\InMemoryEnrollmentRepository;
use Candice\Tests\Unit\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentRequest;
use PHPUnit\Framework\TestCase;

abstract class EnrollmentTest extends TestCase
{
    private InMemoryEnrollmentRepository $enrollmentRepository;
    private IncrementalEnrollmentIdGenerator $enrollmentIdGenerator;
    private RegistrationNumberFactory $registrationNumberFactory;
    private SubmitEnrollmentService $service;

    protected function setUp(): void
    {
        $this->enrollmentRepository = new InMemoryEnrollmentRepository();
        $this->enrollmentIdGenerator = new IncrementalEnrollmentIdGenerator();
        $this->registrationNumberFactory = new RegistrationNumberFactory();
        $this->service = new SubmitEnrollmentService(
            $this->enrollmentRepository,
            $this->enrollmentIdGenerator,
            $this->registrationNumberFactory,
        );
    }

    protected function submitEnrollment(
        string $applicantEmail,
        string $applicantFirstName,
        string $applicantLastName,
        string $applicantPosition,
        string $organizationRegistrationNumberType,
        string $organizationRegistrationNumber
    ): SubmitEnrollmentResponse {
        $request = new SubmitEnrollmentRequest(
            $applicantEmail,
            $applicantFirstName,
            $applicantLastName,
            $applicantPosition,
            $organizationRegistrationNumberType,
            $organizationRegistrationNumber
        );

        return $this->service->execute($request);
    }

    /**
     * @param array{
     *     applicantEmail: string,
     *     applicantFirstName: string,
     *     applicantLastName: string,
     *     applicantPosition: string,
     *     organizationRegistrationNumberType: string,
     *     organizationRegistrationNumber: string
     * } $expected
     */
    protected function assertEnrollmentSubmitted(array $expected, string $enrollmentId): void
    {
        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($enrollmentId));
        $this->assertNotNull($enrollment);
        $this->assertSame($expected['applicantEmail'], $enrollment->getApplicant()->getEmail()->unwrap());
        $this->assertSame($expected['applicantFirstName'], $enrollment->getApplicant()->getFullName()->getFirstName());
        $this->assertSame($expected['applicantLastName'], $enrollment->getApplicant()->getFullName()->getLastName());
        $this->assertSame($expected['applicantPosition'], $enrollment->getApplicant()->getPosition()->unwrap());
        $this->assertSame($expected['organizationRegistrationNumberType'], $enrollment->getRegistrationNumber()->getType());
        $this->assertSame($expected['organizationRegistrationNumber'], $enrollment->getRegistrationNumber()->getValue());
    }
}
