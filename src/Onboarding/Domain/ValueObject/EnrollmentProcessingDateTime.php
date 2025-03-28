<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

use DateTimeImmutable;

final readonly class EnrollmentProcessingDateTime
{
    public function __construct(private DateTimeImmutable $value)
    {
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }
}
