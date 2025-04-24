<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Infrastructure\Service;

use Candice\Contexts\Shared\Domain\Service\ClockInterface;
use DateTimeInterface;
use Override;
use Safe\DateTimeImmutable;

final readonly class SystemClock implements ClockInterface
{
    #[Override]
    public function now(): DateTimeInterface
    {
        return new DateTimeImmutable();
    }
}
