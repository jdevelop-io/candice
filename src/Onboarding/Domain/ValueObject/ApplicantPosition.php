<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

use Candice\Onboarding\Domain\Exception\InvalidApplicantPositionException;

enum ApplicantPosition: string
{
    case Executive = 'executive';

    public function unwrap(): string
    {
        return $this->value;
    }

    public static function fromValue(string $value): self
    {
        return match ($value) {
            self::Executive->value => self::Executive,
            default => throw new InvalidApplicantPositionException($value),
        };
    }
}
