<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentRequestInterface;

final readonly class SubmitEnrollmentRequest implements SubmitEnrollmentRequestInterface
{
    public function __construct(private string $registrationNumberType, private string $registrationNumber)
    {
    }

    public function getRegistrationNumberType(): string
    {
        return $this->registrationNumberType;
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }
}
