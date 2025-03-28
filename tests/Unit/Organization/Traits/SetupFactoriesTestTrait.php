<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization\Traits;

use Candice\Organization\Domain\Factory\RegistrationNumberFactory;

trait SetupFactoriesTestTrait
{
    protected RegistrationNumberFactory $registrationNumberFactory;

    protected function setUpFactories(): void
    {
        $this->registrationNumberFactory = new RegistrationNumberFactory();
    }
}
