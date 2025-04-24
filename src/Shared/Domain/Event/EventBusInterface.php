<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Domain\Event;

interface EventBusInterface
{
    public function publish(DomainEvent $event): void;

    /**
     * @return iterable<DomainEvent>
     */
    public function flush(): iterable;
}
