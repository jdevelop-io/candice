<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentRequest;
use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentResponse;
use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentService;
use Candice\Onboarding\Domain\Event\EnrollmentApprovedEvent;
use Candice\Onboarding\Domain\Service\EnrollmentApprovalService;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait ApproveEnrollmentTestTrait
{
    use EventBusTestTrait;

    private EnrollmentApprovalService $enrollmentApprovalService;
    private ApproveEnrollmentService $approveEnrollmentService;

    protected function setUpApproveEnrollmentService(): void
    {
        $this->enrollmentApprovalService = new EnrollmentApprovalService($this->clock);
        $this->approveEnrollmentService = new ApproveEnrollmentService(
            $this->administratorProvider,
            $this->enrollmentRepository,
            $this->enrollmentApprovalService,
            $this->eventBus,
        );
    }

    protected function approveEnrollment(string $enrollmentId): ApproveEnrollmentResponse
    {
        return $this->approveEnrollmentService->execute(new ApproveEnrollmentRequest($enrollmentId));
    }

    /**
     * @param array{
     *     approvedById: string,
     *     approvedByFirstName: string,
     *     approvedByLastName: string,
     *     approvedAt: string,
     * } $expected
     */
    protected function assertEnrollmentApproved(array $expected, string $enrollmentId): void
    {
        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($enrollmentId));

        $this->assertNotNull($enrollment);
        $this->assertSame('approved', $enrollment->getStatus()->unwrap());

        $approvedBy = $enrollment->getProcessedBy();
        $this->assertNotNull($approvedBy);
        $this->assertSame($expected['approvedById'], $approvedBy->getId()->unwrap());
        $this->assertSame(
            $expected['approvedByFirstName'],
            $approvedBy->getFullName()->getFirstName()
        );
        $this->assertSame($expected['approvedByLastName'], $approvedBy->getFullName()->getLastName());

        $approvedAt = $enrollment->getProcessedAt();
        $this->assertNotNull($approvedAt);
        $this->assertSame(
            $expected['approvedAt'],
            $approvedAt->format($this->clock::DATE_TIME_FORMAT)
        );

        $this->assertEventDispatchedCount(1);
        $this->assertEnrollmentApprovedEvent($enrollmentId);
    }

    protected function assertEnrollmentApprovedEvent(string $enrollmentId): void
    {
        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($enrollmentId));
        $this->assertNotNull($enrollment);

        $events = $this->eventStore->load(new EnrollmentId($enrollmentId));
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof EnrollmentApprovedEvent);
        $this->assertInstanceOf(EnrollmentApprovedEvent::class, $event);
        $this->assertSame($enrollmentId, $event->getAggregateRootId()->unwrap());
        $this->assertSame(
            $enrollment->getApplicant()->getEmail()->unwrap(),
            $event->getApplicant()->getEmail()->unwrap()
        );
        $this->assertSame(
            $enrollment->getApplicant()->getFullName()->getFirstName(),
            $event->getApplicant()->getFullName()->getFirstName()
        );
        $this->assertSame(
            $enrollment->getApplicant()->getFullName()->getLastName(),
            $event->getApplicant()->getFullName()->getLastName()
        );
        $this->assertSame(
            $enrollment->getApplicant()->getPosition()->unwrap(),
            $event->getApplicant()->getPosition()->unwrap()
        );
        $this->assertSame(
            $enrollment->getOrganization()->getRegistrationNumber()->getType(),
            $event->getOrganization()->getRegistrationNumber()->getType()
        );
        $this->assertSame(
            $enrollment->getOrganization()->getRegistrationNumber()->getValue(),
            $event->getOrganization()->getRegistrationNumber()->getValue()
        );
        $this->assertSame(
            $enrollment->getOrganization()->getName()->unwrap(),
            $event->getOrganization()->getName()->unwrap()
        );
        $this->assertSame(
            $enrollment->getProcessedBy()?->getId()->unwrap(),
            $event->getApprovedBy()->getId()->unwrap()
        );
        $this->assertSame(
            $enrollment->getProcessedBy()?->getFullName()->getFirstName(),
            $event->getApprovedBy()->getFullName()->getFirstName()
        );
        $this->assertSame(
            $enrollment->getProcessedBy()?->getFullName()->getLastName(),
            $event->getApprovedBy()->getFullName()->getLastName()
        );
        $this->assertSame(
            $enrollment->getProcessedAt()?->format($this->clock::DATE_TIME_FORMAT),
            $event->getApprovedAt()->format($this->clock::DATE_TIME_FORMAT)
        );
    }
}
