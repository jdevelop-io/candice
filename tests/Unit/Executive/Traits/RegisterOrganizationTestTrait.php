<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Application\RegisterOrganization\RegisterOrganizationResponse;
use Candice\Executive\Application\RegisterOrganization\RegisterOrganizationService;
use Candice\Tests\Unit\Executive\Application\RegisterOrganization\RegisterOrganizationRequest;

trait RegisterOrganizationTestTrait
{
    private RegisterOrganizationService $service;

    protected function setUpRegisterOrganizationService(): void
    {
        $this->service = new RegisterOrganizationService($this->organizationRepository);
    }

    protected function registerOrganization(
        string $organizationId,
        string $organizationName
    ): RegisterOrganizationResponse {
        $request = new RegisterOrganizationRequest($organizationId, $organizationName);

        return $this->service->execute($request);
    }
}
