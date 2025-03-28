<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegisterOrganization;

use Candice\Organization\Domain\Service\OrganizationRegistrationService;

final readonly class RegisterOrganizationService
{
    public function __construct(private OrganizationRegistrationService $organizationRegistrationService)
    {
    }

    public function execute(RegisterOrganizationRequestInterface $request): RegisterOrganizationResponse
    {
        $this->organizationRegistrationService->createRegistrationNumber(
            $request->getOrganizationRegistrationNumberType(),
            $request->getOrganizationRegistrationNumber(),
        );

        return new RegisterOrganizationResponse();
    }
}
