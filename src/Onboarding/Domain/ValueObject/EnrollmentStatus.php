<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

use InvalidArgumentException;

enum EnrollmentStatus: string
{
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function fromValue(string $value): self
    {
        return match ($value) {
            self::PENDING_APPROVAL->unwrap() => self::PENDING_APPROVAL,
            self::APPROVED->unwrap() => self::APPROVED,
            self::REJECTED->unwrap() => self::REJECTED,
            default => throw new InvalidArgumentException("Invalid value for EnrollmentStatus: $value"),
        };
    }

    public function unwrap(): string
    {
        return $this->value;
    }
}
