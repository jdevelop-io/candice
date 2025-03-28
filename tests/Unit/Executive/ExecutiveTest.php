<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Executive\Domain\ValueObject\OrganizationName;
use Candice\Tests\Unit\Executive\Traits\SetupFactoriesTestTrait;
use Candice\Tests\Unit\Executive\Traits\SetupRepositoriesTestTrait;
use Candice\Tests\Unit\Shared\Traits\SetupEventBusTestTrait;
use PHPUnit\Framework\TestCase;

abstract class ExecutiveTest extends TestCase
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

    protected function createOrganization(string $organizationId, string $organizationName): Organization
    {
        $organization = $this->organizationFactory->create(
            new OrganizationId($organizationId),
            new OrganizationName($organizationName)
        );

        $this->organizationRepository->insert($organization);

        return $organization;
    }
}
