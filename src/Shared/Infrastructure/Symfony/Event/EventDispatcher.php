<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Symfony\Event;

use Candice\Shared\Domain\Event\EventDispatcherInterface as EventDispatcherInterfaceAlias;
use Candice\Shared\Domain\Event\EventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final readonly class EventDispatcher implements EventDispatcherInterfaceAlias
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
