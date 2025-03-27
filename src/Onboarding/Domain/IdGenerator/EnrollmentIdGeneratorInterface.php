<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\IdGenerator;

use Candice\Onboarding\Domain\ValueObject\EnrollmentId;

interface EnrollmentIdGeneratorInterface
{
    public function generate(): EnrollmentId;
}
