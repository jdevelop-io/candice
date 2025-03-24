<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;

final readonly class SubmitEnrollmentService
{
    public function __construct(private RegistrationNumberFactory $registrationNumberFactory)
    {
    }

    public function execute(SubmitEnrollmentRequestInterface $request): SubmitEnrollmentResponse
    {
        $this->registrationNumberFactory->create(
            $request->getRegistrationNumberType(),
            $request->getRegistrationNumber()
        );

        return new SubmitEnrollmentResponse();
    }
}
