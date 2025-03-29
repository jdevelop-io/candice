<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Clock;

use Candice\Shared\Domain\Clock\ClockInterface;
use DateTimeImmutable;

final readonly class RealClock implements ClockInterface
{
    public function now(): DatetimeImmutable
    {
        return new \Safe\DateTimeImmutable();
    }
}
