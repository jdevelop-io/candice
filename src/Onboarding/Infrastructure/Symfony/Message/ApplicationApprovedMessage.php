<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Message;

final readonly class ApplicationApprovedMessage
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
