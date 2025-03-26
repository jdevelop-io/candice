<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Exception\EnrollmentInPendingApprovalException;
use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\ValueObject\EnrollmentStatus;

final readonly class SubmitEnrollmentService
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository,
        private RegistrationNumberFactory $registrationNumberFactory
    ) {
    }

    public function execute(SubmitEnrollmentRequestInterface $request): SubmitEnrollmentResponse
    {
        $registrationNumber = $this->registrationNumberFactory->create(
            $request->getRegistrationNumberType(),
            $request->getRegistrationNumber()
        );

        $enrollment = $this->enrollmentRepository->findByRegistrationNumber($registrationNumber);
        if ($enrollment !== null) {
            match ($enrollment->getStatus()) {
                EnrollmentStatus::PENDING_APPROVAL => throw new EnrollmentInPendingApprovalException(),
            };
        }

        $enrollment = Enrollment::submit($registrationNumber);
        $this->enrollmentRepository->insert($enrollment);

        return new SubmitEnrollmentResponse();
    }
}
