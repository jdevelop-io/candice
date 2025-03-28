<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Traits;

use Candice\IdentityAndAccess\Domain\Factory\UserFactory;

trait SetupFactoriesTestTrait
{
    protected UserFactory $userFactory;

    protected function setUpFactories(): void
    {
        $this->userFactory = new UserFactory();
    }
}
