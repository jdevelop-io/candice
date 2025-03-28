<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\RejectEnrollment;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;
use Candice\Onboarding\Domain\Provider\AdministratorProviderInterface;
use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\Service\EnrollmentRejectionService;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Shared\Domain\Event\EventBusInterface;

final readonly class RejectEnrollmentService
{
    public function __construct(
        private AdministratorProviderInterface $administrationProvider,
        private EnrollmentRepositoryInterface $enrollmentRepository,
        private EnrollmentRejectionService $enrollmentRejectionService,
        private EventBusInterface $eventBus,
    ) {
    }

    public function execute(RejectEnrollmentRequestInterface $request): RejectEnrollmentResponse
    {
        $enrollment = $this->getEnrollment($request->getEnrollmentId());
        $rejectedBy = $this->administrationProvider->get();

        $this->enrollmentRejectionService->reject($enrollment, $rejectedBy);

        $events = $enrollment->releaseEvents();
        $this->enrollmentRepository->update($enrollment);
        $this->eventBus->publish($events);

        return new RejectEnrollmentResponse($enrollment->getId()->unwrap());
    }

    private function getEnrollment(string $enrollmentId): Enrollment
    {
        $enrollmentId = new EnrollmentId($enrollmentId);

        $enrollment = $this->enrollmentRepository->findById($enrollmentId);

        return $enrollment ?? throw new EnrollmentNotFoundException($enrollmentId);
    }
}
