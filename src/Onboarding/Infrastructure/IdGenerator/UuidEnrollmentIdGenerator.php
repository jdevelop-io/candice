<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\IdGenerator;

use Candice\Onboarding\Domain\IdGenerator\EnrollmentIdGeneratorInterface;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Symfony\Component\Uid\Uuid;

final readonly class UuidEnrollmentIdGenerator implements EnrollmentIdGeneratorInterface
{
    public function generate(): EnrollmentId
    {
        return new EnrollmentId(Uuid::v4()->toString());
    }
}
