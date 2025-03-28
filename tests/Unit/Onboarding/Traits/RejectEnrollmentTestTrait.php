<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\RejectEnrollment\RejectEnrollmentResponse;
use Candice\Onboarding\Application\RejectEnrollment\RejectEnrollmentService;
use Candice\Onboarding\Domain\Event\EnrollmentRejectedEvent;
use Candice\Onboarding\Domain\Service\EnrollmentRejectionService;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\Onboarding\Application\RejectEnrollment\RejectEnrollmentRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait RejectEnrollmentTestTrait
{
    use EventBusTestTrait;

    private RejectEnrollmentService $service;

    protected function setUpRejectEnrollmentService(): void
    {
        $enrollmentRejectionService = new EnrollmentRejectionService($this->clock);
        $this->service = new RejectEnrollmentService(
            $this->administratorProvider,
            $this->enrollmentRepository,
            $enrollmentRejectionService,
            $this->eventBus,
        );
    }

    protected function rejectEnrollment(string $enrollmentId): RejectEnrollmentResponse
    {
        return $this->service->execute(new RejectEnrollmentRequest($enrollmentId));
    }

    /**
     * @param array{
     *     rejectedById: string,
     *     rejectedByFirstName: string,
     *     rejectedByLastName: string,
     *     rejectedAt: string,
     * } $expected
     */
    protected function assertEnrollmentRejected(array $expected, string $enrollmentId): void
    {
        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($enrollmentId));

        $this->assertNotNull($enrollment);
        $this->assertSame('rejected', $enrollment->getStatus()->unwrap());

        $rejectedBy = $enrollment->getProcessedBy();
        $this->assertNotNull($rejectedBy);
        $this->assertSame($expected['rejectedById'], $rejectedBy->getId()->unwrap());
        $this->assertSame(
            $expected['rejectedByFirstName'],
            $rejectedBy->getFullName()->getFirstName()
        );
        $this->assertSame($expected['rejectedByLastName'], $rejectedBy->getFullName()->getLastName());

        $rejectedAt = $enrollment->getProcessedAt();
        $this->assertNotNull($rejectedAt);
        $this->assertSame(
            $expected['rejectedAt'],
            $rejectedAt->format($this->clock::DATE_TIME_FORMAT)
        );

        $this->assertEventDispatchedCount(1);
        $this->assertEnrollmentRejectedEvent($enrollmentId);
    }

    protected function assertEnrollmentRejectedEvent(string $enrollmentId): void
    {
        $events = $this->eventStore->load(new EnrollmentId($enrollmentId));
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof EnrollmentRejectedEvent);
        $this->assertInstanceOf(EnrollmentRejectedEvent::class, $event);
        $this->assertSame($enrollmentId, $event->getAggregateRootId()->unwrap());
    }
}
