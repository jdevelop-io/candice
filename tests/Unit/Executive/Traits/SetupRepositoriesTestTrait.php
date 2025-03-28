<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Infrastructure\Repository\InMemoryOrganizationRepository;

trait SetupRepositoriesTestTrait
{
    protected InMemoryOrganizationRepository $organizationRepository;

    protected function setUpRepositories(): void
    {
        $this->organizationRepository = new InMemoryOrganizationRepository();
    }
}
