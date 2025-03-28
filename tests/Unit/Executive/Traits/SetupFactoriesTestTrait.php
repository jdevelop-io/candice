<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Domain\Factory\ExecutiveFactory;
use Candice\Executive\Domain\Factory\OrganizationFactory;

trait SetupFactoriesTestTrait
{
    protected OrganizationFactory $organizationFactory;
    protected ExecutiveFactory $executiveFactory;

    protected function setUpFactories(): void
    {
        $this->organizationFactory = new OrganizationFactory();
        $this->executiveFactory = new ExecutiveFactory();
    }
}
