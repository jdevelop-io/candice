<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive;

use Candice\Tests\Unit\Executive\Traits\SetupFactoriesTestTrait;
use Candice\Tests\Unit\Executive\Traits\SetupRepositoriesTestTrait;
use Candice\Tests\Unit\Shared\Traits\SetupEventBusTestTrait;
use PHPUnit\Framework\TestCase;

abstract class ExecutiveTest extends TestCase
{
    use SetupEventBusTestTrait;
    use SetupFactoriesTestTrait;
    use SetupRepositoriesTestTrait;

    protected function setUp(): void
    {
        $this->setUpFactories();
        $this->setUpRepositories();
        $this->setUpEventBus();
    }
}
