<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\SubmitEnrollment;

interface SubmitEnrollmentRequestInterface
{
    public function getApplicantEmail(): string;

    public function getApplicantFirstName(): string;

    public function getApplicantLastName(): string;

    public function getApplicantPosition(): string;

    public function getOrganizationRegistrationNumberType(): string;

    public function getOrganizationRegistrationNumber(): string;
}
