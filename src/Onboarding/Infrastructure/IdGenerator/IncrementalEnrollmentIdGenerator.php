<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\IdGenerator;

use Candice\Onboarding\Domain\IdGenerator\EnrollmentIdGeneratorInterface;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;

final class IncrementalEnrollmentIdGenerator implements EnrollmentIdGeneratorInterface
{
    private int $lastId = 0;

    public function generate(): EnrollmentId
    {
        return new EnrollmentId((string)++$this->lastId);
    }
}
