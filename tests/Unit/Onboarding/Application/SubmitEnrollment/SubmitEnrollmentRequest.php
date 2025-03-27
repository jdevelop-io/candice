<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Application\SubmitEnrollment;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentRequestInterface;

final readonly class SubmitEnrollmentRequest implements SubmitEnrollmentRequestInterface
{
    public function __construct(
        private string $applicantEmail,
        private string $applicantFirstName,
        private string $applicantLastName,
        private string $applicantPosition,
        private string $organizationRegistrationNumberType,
        private string $organizationRegistrationNumber,
        private string $organizationName,
    ) {
    }

    public function getApplicantEmail(): string
    {
        return $this->applicantEmail;
    }

    public function getApplicantFirstName(): string
    {
        return $this->applicantFirstName;
    }

    public function getApplicantLastName(): string
    {
        return $this->applicantLastName;
    }

    public function getApplicantPosition(): string
    {
        return $this->applicantPosition;
    }

    public function getOrganizationRegistrationNumberType(): string
    {
        return $this->organizationRegistrationNumberType;
    }

    public function getOrganizationRegistrationNumber(): string
    {
        return $this->organizationRegistrationNumber;
    }

    public function getOrganizationName(): string
    {
        return $this->organizationName;
    }
}
