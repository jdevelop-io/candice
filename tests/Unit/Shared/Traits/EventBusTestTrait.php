<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Shared\Traits;

trait EventBusTestTrait
{
    protected function assertEventDispatchedCount(int $expected): void
    {
        $this->assertCount($expected, $this->eventDispatcher);
    }
}
