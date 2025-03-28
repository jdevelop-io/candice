<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Domain\Factory\OrganizationFactory;

trait SetupFactoriesTestTrait
{
    private OrganizationFactory $organizationFactory;

    protected function setUpFactories(): void
    {
        $this->organizationFactory = new OrganizationFactory();
    }
}
