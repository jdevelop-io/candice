<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\DTO;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class SubmitEnrollmentRequest implements SubmitEnrollmentRequestInterface
{
    public function __construct(
        #[Assert\Email]
        private string $applicantEmail,
        #[Assert\NotBlank]
        private string $applicantFirstName,
        #[Assert\NotBlank]
        private string $applicantLastName,
        #[Assert\Choice(choices: ['executive'])]
        private string $applicantPosition,
        #[Assert\Choice(choices: ['siren'])]
        private string $organizationRegistrationNumberType,
        #[Assert\NotBlank]
        private string $organizationRegistrationNumber,
        #[Assert\NotBlank]
        private string $organizationName
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
