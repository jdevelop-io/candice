<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization\Traits;

use Candice\Organization\Domain\Factory\OrganizationFactory;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;

trait SetupFactoriesTestTrait
{
    protected OrganizationFactory $organizationFactory;
    protected RegistrationNumberFactory $registrationNumberFactory;

    protected function setUpFactories(): void
    {
        $this->organizationFactory = new OrganizationFactory();
        $this->registrationNumberFactory = new RegistrationNumberFactory();
    }
}
