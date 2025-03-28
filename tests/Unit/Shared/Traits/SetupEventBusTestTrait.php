<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Shared\Traits;

use Candice\Shared\Infrastructure\Event\InMemoryEventBus;
use Candice\Shared\Infrastructure\Event\InMemoryEventDispatcher;
use Candice\Shared\Infrastructure\Event\InMemoryEventStore;

trait SetupEventBusTestTrait
{
    protected InMemoryEventStore $eventStore;
    protected InMemoryEventDispatcher $eventDispatcher;
    protected InMemoryEventBus $eventBus;

    public function setUpEventBus(): void
    {
        $this->eventStore = new InMemoryEventStore();
        $this->eventDispatcher = new InMemoryEventDispatcher();
        $this->eventBus = new InMemoryEventBus($this->eventStore, $this->eventDispatcher);
    }
}
