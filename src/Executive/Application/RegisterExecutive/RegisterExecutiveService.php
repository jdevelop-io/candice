<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterExecutive;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\Exception\OrganizationNotFoundException;
use Candice\Executive\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Executive\Domain\ValueObject\OrganizationId;

final readonly class RegisterExecutiveService
{
    public function __construct(private OrganizationRepositoryInterface $organizationRepository)
    {
    }

    public function execute(RegisterExecutiveRequestInterface $request): RegisterExecutiveResponse
    {
        $organization = $this->getOrganization($request->getOrganizationId());

        return new RegisterExecutiveResponse();
    }

    private function getOrganization(string $organizationId): Organization
    {
        $organizationId = new OrganizationId($organizationId);

        $organization = $this->organizationRepository->findById($organizationId);

        return $organization ?? throw new OrganizationNotFoundException($organizationId);
    }
}
