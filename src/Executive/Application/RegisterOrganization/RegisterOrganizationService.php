<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterOrganization;

use Candice\Executive\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Executive\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Executive\Domain\ValueObject\OrganizationId;

final readonly class RegisterOrganizationService
{
    public function __construct(private OrganizationRepositoryInterface $organizationRepository)
    {
    }

    public function execute(RegisterOrganizationRequestInterface $request): RegisterOrganizationResponse
    {
        $this->guardAgainstDuplicateOrganization($request->getOrganizationId());

        return new RegisterOrganizationResponse();
    }

    private function guardAgainstDuplicateOrganization(string $organizationId): void
    {
        $organizationId = new OrganizationId($organizationId);

        if (!$this->organizationRepository->existsById($organizationId)) {
            return;
        }

        throw new OrganizationAlreadyRegisteredException($organizationId);
    }
}
