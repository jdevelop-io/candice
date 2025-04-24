<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Infrastructure\EventBus;

use Candice\Contexts\Shared\Domain\Event\DomainEvent;
use Candice\Contexts\Shared\Domain\Event\EventBusInterface;
use Override;

final class InMemoryEventBus implements EventBusInterface
{
    /**
     * @var array<DomainEvent>
     */
    private array $events = [];

    #[Override]
    public function publish(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    #[Override]
    public function flush(): iterable
    {
        $events = $this->events;
        $this->events = [];

        yield from $events;
    }
}
