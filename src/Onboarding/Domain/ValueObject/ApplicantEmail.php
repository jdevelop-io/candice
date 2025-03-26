<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

use Candice\Onboarding\Domain\Exception\InvalidApplicantEmailException;

final readonly class ApplicantEmail
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    public function unwrap(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidApplicantEmailException($value);
        }
    }
}
