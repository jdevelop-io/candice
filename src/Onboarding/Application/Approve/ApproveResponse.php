<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Approve;

final readonly class ApproveResponse
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
