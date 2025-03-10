<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Event;

use Candice\Shared\Domain\Event\EventInterface;

final readonly class ApplicationApprovedEvent implements EventInterface
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
