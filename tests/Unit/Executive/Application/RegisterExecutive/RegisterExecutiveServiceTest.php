<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Application\RegisterExecutive;

use Candice\Executive\Domain\Exception\ExecutiveAlreadyRegistered;
use Candice\Executive\Domain\Exception\InvalidExecutiveEmailException;
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

        $this->createOrganization('ExistingOrganizationId', 'OrganizationName');
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

    public function testExecutiveEmailShouldBeValid(): void
    {
        $this->expectException(InvalidExecutiveEmailException::class);

        $this->registerExecutive(
            'ExistingOrganizationId',
            'paul-henry.dumont',
            'Paul-henry',
            'dumont'
        );
    }

    public function testExecutiveEmailIsUnique(): void
    {
        $this->createExecutive(
            'ExistingOrganizationId',
            'paul-henry.dumont@example.com',
            'Paul-henry',
            'dumont'
        );

        $this->expectException(ExecutiveAlreadyRegistered::class);

        $this->registerExecutive(
            'ExistingOrganizationId',
            'paul-henry.dumont@example.com',
            'Paul-henry',
            'dumont'
        );
    }

    public function testExecutiveShouldBeRegistered(): void
    {
        $response = $this->registerExecutive(
            'ExistingOrganizationId',
            'paul-henry.dumont@example.com',
            'Paul-henry',
            'dumont'
        );

        $this->assertExecutiveRegistered(
            [
                'organizationId' => 'ExistingOrganizationId',
                'organizationName' => 'OrganizationName',
                'executiveFirstName' => 'Paul-Henry',
                'executiveLastName' => 'DUMONT',
            ],
            $response->getExecutiveEmail()
        );
    }
}
