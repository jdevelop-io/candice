<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Domain\Factory\ExecutiveFactory;
use Candice\Executive\Domain\Factory\OrganizationFactory;

trait SetupFactoriesTestTrait
{
    private OrganizationFactory $organizationFactory;
    private ExecutiveFactory $executiveFactory;

    protected function setUpFactories(): void
    {
        $this->organizationFactory = new OrganizationFactory();
        $this->executiveFactory = new ExecutiveFactory();
    }
}
