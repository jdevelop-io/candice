<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

interface EventDispatcherInterface
{
    public function dispatch(EventInterface $event): void;
}
