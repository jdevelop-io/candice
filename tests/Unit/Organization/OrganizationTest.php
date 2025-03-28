<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization;

use Candice\Tests\Unit\Organization\Traits\SetupFactoriesTestTrait;
use Candice\Tests\Unit\Organization\Traits\SetupRepositoriesTestTrait;
use Candice\Tests\Unit\Shared\Traits\SetupEventBusTestTrait;
use PHPUnit\Framework\TestCase;

abstract class OrganizationTest extends TestCase
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
