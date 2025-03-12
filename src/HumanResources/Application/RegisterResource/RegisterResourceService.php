<?php

declare(strict_types=1);

namespace Candice\HumanResources\Application\RegisterResource;

use Candice\HumanResources\Domain\Entity\Organization;
use Candice\HumanResources\Domain\Entity\Resource;
use Candice\HumanResources\Domain\Exception\OrganizationNotFoundException;
use Candice\HumanResources\Domain\Repository\ResourceRepositoryInterface;
use Candice\HumanResources\Domain\Service\OrganizationExistenceCheckerInterface;
use Candice\HumanResources\Domain\ValueObject\FullName;

final readonly class RegisterResourceService
{
    public function __construct(
        private ResourceRepositoryInterface $resourceRepository,
        private OrganizationExistenceCheckerInterface $organizationExistenceChecker
    ) {
    }

    public function execute(RegisterResourceRequest $request): RegisterResourceResponse
    {
        if (!$this->organizationExistenceChecker->existsById($request->getOrganizationId())) {
            throw new OrganizationNotFoundException($request->getOrganizationId());
        }

        $resource = new Resource(
            new Organization($request->getOrganizationId()),
            new FullName($request->getFirstName(), $request->getLastName())
        );

        $this->resourceRepository->save($resource);

        return new RegisterResourceResponse($resource->getId());
    }
}
