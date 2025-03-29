<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Clock;

use Candice\Shared\Domain\Clock\ClockInterface;
use DateTimeImmutable;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;

final readonly class RealClock implements ClockInterface
{
    public function now(): DatetimeImmutable
    {
        return new SafeDateTimeImmutable();
    }
}
