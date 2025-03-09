<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Approve;

final readonly class ApproveResponse
{
    public function __construct(private string $id, private string $status)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
