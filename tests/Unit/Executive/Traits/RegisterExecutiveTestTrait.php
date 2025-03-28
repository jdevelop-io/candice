<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Application\RegisterExecutive\RegisterExecutiveResponse;
use Candice\Executive\Application\RegisterExecutive\RegisterExecutiveService;
use Candice\Tests\Unit\Executive\Application\RegisterExecutive\RegisterExecutiveRequest;

trait RegisterExecutiveTestTrait
{
    private RegisterExecutiveService $service;

    protected function setUpRegisterExecutiveService(): void
    {
        $this->service = new RegisterExecutiveService($this->organizationRepository, $this->executiveRepository);
    }

    protected function registerExecutive(
        string $organizationId,
        string $executiveEmail,
        string $executiveFirstName,
        string $executiveLastName
    ): RegisterExecutiveResponse {
        $request = new RegisterExecutiveRequest(
            $organizationId,
            $executiveEmail,
            $executiveFirstName,
            $executiveLastName
        );

        return $this->service->execute($request);
    }
}
