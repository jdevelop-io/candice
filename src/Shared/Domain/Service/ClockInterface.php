<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Domain\Service;

use DateTimeInterface;

interface ClockInterface
{
    public function now(): DateTimeInterface;
}
