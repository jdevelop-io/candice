<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\ApproveEnrollment;

use Candice\Onboarding\Domain\Entity\Enrollment;
use Candice\Onboarding\Domain\Exception\EnrollmentNotFoundException;
use Candice\Onboarding\Domain\Repository\EnrollmentRepositoryInterface;
use Candice\Onboarding\Domain\Service\EnrollmentApprovalService;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;

final readonly class ApproveEnrollmentService
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository,
        private EnrollmentApprovalService $enrollmentApprovalService
    ) {
    }

    public function execute(ApproveEnrollmentRequestInterface $request): ApproveEnrollmentResponse
    {
        $enrollment = $this->getEnrollment($request->getEnrollmentId());

        $this->enrollmentApprovalService->approve($enrollment);

        $this->enrollmentRepository->update($enrollment);

        return new ApproveEnrollmentResponse($enrollment->getId()->unwrap());
    }

    private function getEnrollment(string $enrollmentId): Enrollment
    {
        $enrollment = $this->enrollmentRepository->findById(new EnrollmentId($enrollmentId));

        return $enrollment ?? throw new EnrollmentNotFoundException($enrollmentId);
    }
}
