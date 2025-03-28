<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegisterOrganization;

final readonly class RegisterOrganizationService
{
    public function execute(RegisterOrganizationRequestInterface $request): RegisterOrganizationResponse
    {
        return new RegisterOrganizationResponse();
    }
}
