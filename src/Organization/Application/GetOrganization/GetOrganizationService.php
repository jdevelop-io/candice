<?php

declare(strict_types=1);

namespace Candice\Organization\Application\GetOrganization;

use Candice\Organization\Domain\Exception\OrganizationNotFoundException;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\Repository\OrganizationRepositoryInterface;

final readonly class GetOrganizationService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private RegistrationNumberFactory $registrationNumberFactory
    ) {
    }

    public function execute(GetOrganizationRequest $request): GetOrganizationResponse
    {
        $registrationNumber = $this->registrationNumberFactory->create($request->getRegistrationNumber());
        $organization = $this->organizationRepository->findByRegistrationNumber($registrationNumber);
        if ($organization === null) {
            throw new OrganizationNotFoundException($request->getRegistrationNumber());
        }

        return new GetOrganizationResponse(
            $organization->getRegistrationNumber()->unwrap(),
            $organization->getName()
        );
    }
}
