<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentResponse;
use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentService;
use Candice\Onboarding\Domain\Event\EnrollmentSubmittedEvent;
use Candice\Onboarding\Domain\Service\EnrollmentSubmissionService;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait SubmitEnrollmentTestTrait
{
    use EventBusTestTrait;

    private SubmitEnrollmentService $service;

    public function setUpSubmitEnrollmentService(): void
    {
        $this->service = new SubmitEnrollmentService(
            $this->enrollmentRepository,
            new EnrollmentSubmissionService(
                $this->registrationNumberFactory,
                $this->applicantFactory,
                $this->organizationFactory,
                $this->enrollmentFactory
            ),
            $this->eventBus,
        );
    }

    protected function submitEnrollment(
        string $applicantEmail,
        string $applicantFirstName,
        string $applicantLastName,
        string $applicantPosition,
        string $organizationRegistrationNumberType,
        string $organizationRegistrationNumber,
        string $organizationName
    ): SubmitEnrollmentResponse {
        $request = new SubmitEnrollmentRequest(
            $applicantEmail,
            $applicantFirstName,
            $applicantLastName,
            $applicantPosition,
            $organizationRegistrationNumberType,
            $organizationRegistrationNumber,
            $organizationName
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
     *     organizationRegistrationNumber: string,
     *     organizationName: string
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
        $this->assertSame(
            $expected['organizationRegistrationNumberType'],
            $enrollment->getOrganization()->getRegistrationNumber()->getType()
        );
        $this->assertSame(
            $expected['organizationRegistrationNumber'],
            $enrollment->getOrganization()->getRegistrationNumber()->getValue()
        );
        $this->assertSame($expected['organizationName'], $enrollment->getOrganization()->getName()->unwrap());
        $this->assertSame('pending_approval', $enrollment->getStatus()->unwrap());
        $this->assertEventDispatchedCount(1);
        $this->assertEnrollmentSubmittedEvent($enrollmentId);
    }

    private function assertEnrollmentSubmittedEvent(string $enrollmentId): void
    {
        $events = $this->eventStore->load(new EnrollmentId($enrollmentId));
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof EnrollmentSubmittedEvent);
        $this->assertInstanceOf(EnrollmentSubmittedEvent::class, $event);
        $this->assertSame($enrollmentId, $event->getAggregateRootId()->unwrap());
    }
}
