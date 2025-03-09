<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\List;

final readonly class ListRequest
{
    public function __construct(private ?string $status = null)
    {
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
