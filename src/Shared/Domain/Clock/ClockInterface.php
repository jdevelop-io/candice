<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Clock;

use DateTimeImmutable;

interface ClockInterface
{
    public function now(): DatetimeImmutable;
}
