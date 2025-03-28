<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Application\RegisterExecutive;

use Candice\Executive\Domain\Exception\OrganizationNotFoundException;
use Candice\Tests\Unit\Executive\ExecutiveTest;
use Candice\Tests\Unit\Executive\Traits\RegisterExecutiveTestTrait;

final class RegisterExecutiveServiceTest extends ExecutiveTest
{
    use RegisterExecutiveTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRegisterExecutiveService();
    }

    public function testOrganizationShouldExist(): void
    {
        $this->expectException(OrganizationNotFoundException::class);

        $this->registerExecutive(
            'InvalidOrganizationId',
            'paul-henry.dumont@example.com',
            'Paul-henry',
            'dumont'
        );
    }
}
