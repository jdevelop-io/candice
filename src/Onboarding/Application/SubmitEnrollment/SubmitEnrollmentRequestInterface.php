<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\SubmitEnrollment;

interface SubmitEnrollmentRequestInterface
{
    public function getRegistrationNumberType(): string;

    public function getRegistrationNumber(): string;
}
