<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Infrastructure\Service;

use Candice\Contexts\Shared\Domain\Service\ClockInterface;
use DateTimeInterface;
use Override;

final readonly class FrozenClock implements ClockInterface
{
    public function __construct(
        private DateTimeInterface $frozenDateTime,
    ) {
    }

    #[Override]
    public function now(): DateTimeInterface
    {
        return $this->frozenDateTime;
    }
}
