<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentResponse;
use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentService;
use Candice\Onboarding\Domain\Event\EnrollmentApprovedEvent;
use Candice\Onboarding\Domain\Service\EnrollmentApprovalService;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentRequest;
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
        $events = $this->eventStore->load(new EnrollmentId($enrollmentId));
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof EnrollmentApprovedEvent);
        $this->assertInstanceOf(EnrollmentApprovedEvent::class, $event);
        $this->assertSame($enrollmentId, $event->getAggregateRootId()->unwrap());
    }
}
