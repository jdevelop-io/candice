<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization\Traits;

use Candice\Organization\Infrastructure\Repository\InMemoryOrganizationRepository;

trait SetupRepositoriesTestTrait
{
    protected InMemoryOrganizationRepository $organizationRepository;

    protected function setUpRepositories(): void
    {
        $this->organizationRepository = new InMemoryOrganizationRepository();
    }
}
