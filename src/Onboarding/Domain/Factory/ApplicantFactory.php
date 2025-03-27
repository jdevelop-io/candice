<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Factory;

use Candice\Onboarding\Domain\Entity\Applicant;
use Candice\Onboarding\Domain\ValueObject\ApplicantEmail;
use Candice\Onboarding\Domain\ValueObject\ApplicantFullName;
use Candice\Onboarding\Domain\ValueObject\ApplicantPosition;

final readonly class ApplicantFactory
{
    public function create(
        string $email,
        string $firstName,
        string $lastName,
        string $position
    ): Applicant {
        return new Applicant(
            new ApplicantEmail($email),
            new ApplicantFullName($firstName, $lastName),
            ApplicantPosition::fromValue($position)
        );
    }
}
