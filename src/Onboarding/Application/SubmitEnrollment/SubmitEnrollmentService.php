<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Domain\Entity\Applicant;
use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Exception\EnrollmentInPendingApprovalException;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\ValueObject\ApplicantEmail;
use Candice\Onboarding\Domain\ValueObject\ApplicantFullName;
use Candice\Onboarding\Domain\ValueObject\ApplicantPosition;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;
use Candice\Shared\Domain\Event\EventPublisherInterface;
use Candice\Shared\Domain\Event\EventStoreInterface;

final readonly class SubmitEnrollmentService
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository,
        private EnrollmentIdGeneratorInterface $enrollmentIdGenerator,
        private EventStoreInterface $eventStore,
        private EventPublisherInterface $eventPublisher,
        private RegistrationNumberFactory $registrationNumberFactory,
    ) {
    }

    public function execute(SubmitEnrollmentRequestInterface $request): SubmitEnrollmentResponse
    {
        $registrationNumber = $this->registrationNumberFactory->create(
            $request->getOrganizationRegistrationNumberType(),
            $request->getOrganizationRegistrationNumber()
        );

        $enrollment = $this->enrollmentRepository->findByRegistrationNumber($registrationNumber);
        if ($enrollment !== null) {
            match ($enrollment->getStatus()) {
                EnrollmentStatus::PENDING_APPROVAL => throw new EnrollmentInPendingApprovalException(),
                // TODO: EnrollmentStatus::APPROVED => throw new EnrollmentAlreadyApprovedException(),
                // TODO: EnrollmentStatus::REJECTED => null,
            };
        }

        $applicantEmail = new ApplicantEmail($request->getApplicantEmail());
        $applicantFullName = new ApplicantFullName($request->getApplicantFirstName(), $request->getApplicantLastName());
        $applicantPosition = ApplicantPosition::fromValue($request->getApplicantPosition());
        $applicant = new Applicant($applicantEmail, $applicantFullName, $applicantPosition);

        $id = $this->enrollmentIdGenerator->generate();
        $enrollment = Enrollment::submit($id, $registrationNumber, $applicant);

        $this->enrollmentRepository->insert($enrollment);

        $events = $enrollment->releaseEvents();
        $this->eventStore->append($events);
        $this->eventPublisher->publish($events);

        return new SubmitEnrollmentResponse($enrollment->getId()->unwrap());
    }
}
