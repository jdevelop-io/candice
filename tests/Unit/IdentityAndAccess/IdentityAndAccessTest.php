<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess;

use Candice\Tests\Unit\IdentityAndAccess\Traits\SetupFactoriesTestTrait;
use Candice\Tests\Unit\IdentityAndAccess\Traits\SetupRepositoriesTestTrait;
use Candice\Tests\Unit\Shared\Traits\SetupEventBusTestTrait;
use PHPUnit\Framework\TestCase;

abstract class IdentityAndAccessTest extends TestCase
{
    use SetupFactoriesTestTrait;
    use SetupRepositoriesTestTrait;
    use SetupEventBusTestTrait;

    protected function setUp(): void
    {
        $this->setUpFactories();
        $this->setUpRepositories();
        $this->setUpEventBus();
    }
}
