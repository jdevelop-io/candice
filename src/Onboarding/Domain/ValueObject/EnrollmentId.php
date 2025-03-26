<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

final readonly class EnrollmentId
{
    public function __construct(private string $value)
    {
    }

    public function unwrap(): string
    {
        return $this->value;
    }
}
