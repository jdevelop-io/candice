<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Registration;

final readonly class RegistrationResponse
{
    public function __construct(private string $applicationId)
    {
    }

    public function getApplicationId(): string
    {
        return $this->applicationId;
    }
}
