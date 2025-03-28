<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentResponse;
use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentService;
use Candice\Onboarding\Domain\Entity\Administrator;
use Candice\Onboarding\Domain\Event\EnrollmentApprovedEvent;
use Candice\Onboarding\Domain\Service\EnrollmentApprovalService;
use Candice\Onboarding\Domain\ValueObject\AdministratorFullName;
use Candice\Onboarding\Domain\ValueObject\AdministratorId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Infrastructure\Provider\InMemoryAdministratorProvider;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Infrastructure\Clock\FrozenClock;
use Candice\Tests\Unit\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait ApproveEnrollmentTestTrait
{
    use EventBusTestTrait;

    private EnrollmentApprovalService $enrollmentApprovalService;
    private ApproveEnrollmentService $approveEnrollmentService;
    private InMemoryAdministratorProvider $administratorProvider;
    private FrozenClock $clock;

    protected function setUpApproveEnrollmentService(): void
    {
        $this->clock = new FrozenClock('2025-03-28 13:56:11');
        $this->administratorProvider = new InMemoryAdministratorProvider();
        $this->enrollmentApprovalService = new EnrollmentApprovalService($this->clock);
        $this->approveEnrollmentService = new ApproveEnrollmentService(
            $this->administratorProvider,
            $this->enrollmentRepository,
            $this->enrollmentApprovalService,
            $this->eventBus,
        );
    }

    protected function defineAdministrator(
        string $administratorId,
        string $administratorFirstName,
        string $administratorLastName
    ): void {
        $this->administratorProvider->define(
            new Administrator(
                new AdministratorId($administratorId),
                new AdministratorFullName($administratorFirstName, $administratorLastName)
            )
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
        $this->assertSame($expected['approvedById'], $enrollment->getProcessedBy()->getId()->unwrap());
        $this->assertSame(
            $expected['approvedByFirstName'],
            $enrollment->getProcessedBy()->getFullName()->getFirstName()
        );
        $this->assertSame($expected['approvedByLastName'], $enrollment->getProcessedBy()->getFullName()->getLastName());
        $this->assertSame(
            $expected['approvedAt'],
            $enrollment->getProcessedAt()->format($this->clock::DATE_TIME_FORMAT)
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
