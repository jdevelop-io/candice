<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Application\RegisterOrganization\RegisterOrganizationService;

trait RegisterOrganizationTestTrait
{
    private RegisterOrganizationService $service;

    protected function setUpRegisterOrganizationService(): void
    {
        $this->service = new RegisterOrganizationService();
    }
}
