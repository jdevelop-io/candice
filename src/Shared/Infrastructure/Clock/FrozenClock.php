<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Clock;

use Candice\Shared\Domain\Clock\ClockInterface;
use DateTimeImmutable;

final readonly class FrozenClock implements ClockInterface
{
    public const string DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    private DateTimeImmutable $now;

    public function __construct(string $dateTime)
    {
        $this->now = \Safe\DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $dateTime);
    }

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }

    public function format(): string
    {
        return $this->now->format(self::DATE_TIME_FORMAT);
    }
}
