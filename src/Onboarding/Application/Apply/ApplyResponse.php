<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Apply;

final readonly class ApplyResponse
{
    public function __construct(private string $applicationId)
    {
    }

    public function getApplicationId(): string
    {
        return $this->applicationId;
    }
}
