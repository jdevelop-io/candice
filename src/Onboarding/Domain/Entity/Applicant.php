<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\ValueObject\ApplicantEmail;
use Candice\Onboarding\Domain\ValueObject\ApplicantFullName;
use Candice\Onboarding\Domain\ValueObject\ApplicantPosition;

final readonly class Applicant
{
    public function __construct(
        private ApplicantEmail $email,
        private ApplicantFullName $fullName,
        private ApplicantPosition $position,
    ) {
    }

    public function getEmail(): ApplicantEmail
    {
        return $this->email;
    }

    public function getFullName(): ApplicantFullName
    {
        return $this->fullName;
    }

    public function getPosition(): ApplicantPosition
    {
        return $this->position;
    }
}
