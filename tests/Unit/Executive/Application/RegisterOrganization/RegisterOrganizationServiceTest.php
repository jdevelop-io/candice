<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Application\RegisterOrganization;

use Candice\Executive\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Tests\Unit\Executive\ExecutiveTest;
use Candice\Tests\Unit\Executive\Traits\RegisterOrganizationTestTrait;

final class RegisterOrganizationServiceTest extends ExecutiveTest
{
    use RegisterOrganizationTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRegisterOrganizationService();
    }

    public function testOrganizationIsUniqueById(): void
    {
        $this->createOrganization('OrganizationId', 'OrganizationName');

        $this->expectException(OrganizationAlreadyRegisteredException::class);

        $this->registerOrganization('OrganizationId', 'OrganizationName');
    }
}
