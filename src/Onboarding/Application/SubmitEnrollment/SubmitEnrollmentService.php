<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\Service\EnrollmentSubmissionService;
use Candice\Shared\Domain\Event\EventBusInterface;

final readonly class SubmitEnrollmentService
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository,
        private EnrollmentSubmissionService $enrollmentSubmissionService,
        private EventBusInterface $eventBus,
    ) {
    }

    public function execute(SubmitEnrollmentRequestInterface $request): SubmitEnrollmentResponse
    {
        $applicant = $this->enrollmentSubmissionService->createApplicant(
            $request->getApplicantEmail(),
            $request->getApplicantFirstName(),
            $request->getApplicantLastName(),
            $request->getApplicantPosition()
        );

        $organization = $this->enrollmentSubmissionService->createOrganization(
            $request->getOrganizationRegistrationNumberType(),
            $request->getOrganizationRegistrationNumber(),
            $request->getOrganizationName()
        );

        $registrationNumber = $this->enrollmentSubmissionService->createRegistrationNumber(
            $request->getOrganizationRegistrationNumberType(),
            $request->getOrganizationRegistrationNumber()
        );
        $existingEnrollment = $this->enrollmentRepository->findByOrganizationRegistrationNumber($registrationNumber);

        $enrollment = $this->enrollmentSubmissionService->submit($existingEnrollment, $applicant, $organization);

        $events = $enrollment->releaseEvents();
        $this->enrollmentRepository->insert($enrollment);
        $this->eventBus->publish($events);

        return new SubmitEnrollmentResponse($enrollment->getId()->unwrap());
    }
}
