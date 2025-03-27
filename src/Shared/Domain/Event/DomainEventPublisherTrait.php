<?php

declare(strict_types=1);

namespace Candice\Shared\Domain\Event;

trait DomainEventPublisherTrait
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    public function record(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return iterable<DomainEvent>
     */
    public function releaseEvents(): iterable
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
