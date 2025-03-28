<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization\Traits;

use Candice\Organization\Application\RegisterOrganization\RegisterOrganizationResponse;
use Candice\Organization\Application\RegisterOrganization\RegisterOrganizationService;
use Candice\Tests\Unit\Organization\Application\RegisterOrganization\RegisterOrganizationRequest;

trait RegisterOrganizationTestTrait
{
    private RegisterOrganizationService $service;

    protected function setUpRegisterOrganizationService(): void
    {
        $this->service = new RegisterOrganizationService();
    }

    protected function registerOrganization(
        string $registrationNumberType,
        string $registrationNumber,
        string $name
    ): RegisterOrganizationResponse {
        $request = new RegisterOrganizationRequest(
            $registrationNumberType,
            $registrationNumber,
            $name
        );

        return $this->service->execute($request);
    }
}
