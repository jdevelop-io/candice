<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegistrationNumberValidation;

final readonly class RegistrationNumberValidationResponse
{
    public function __construct(private bool $valid, private ?string $reason = null)
    {
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
