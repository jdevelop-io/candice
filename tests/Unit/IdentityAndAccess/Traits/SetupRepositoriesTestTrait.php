<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Traits;

use Candice\IdentityAndAccess\Infrastructure\Repository\InMemoryUserRepository;

trait SetupRepositoriesTestTrait
{
    protected InMemoryUserRepository $userRepository;

    protected function setUpRepositories(): void
    {
        $this->userRepository = new InMemoryUserRepository();
    }
}
