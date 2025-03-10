<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Application\RegisterProspect;

use Candice\CustomerRelationship\Domain\Entity\Organization;
use Candice\CustomerRelationship\Domain\Entity\Prospect;
use Candice\CustomerRelationship\Domain\Exception\OrganizationNotFoundException;
use Candice\CustomerRelationship\Domain\Exception\ProspectAlreadyRegisteredException;
use Candice\CustomerRelationship\Domain\Factory\RegistrationNumberFactory;
use Candice\CustomerRelationship\Domain\Repository\ProspectRepositoryInterface;
use Candice\CustomerRelationship\Domain\Service\OrganizationCheckerInterface;

final readonly class RegisterProspectService
{
    public function __construct(
        private ProspectRepositoryInterface $prospectRepository,
        private OrganizationCheckerInterface $organizationChecker,
        private RegistrationNumberFactory $registrationNumberFactory
    ) {
    }

    public function execute(RegisterProspectRequest $request): RegisterProspectResponse
    {
        if (!$this->organizationChecker->existsById($request->getOrganizationId())) {
            throw new OrganizationNotFoundException($request->getOrganizationId());
        }

        $registrationNumber = $this->registrationNumberFactory->create($request->getRegistrationNumber());
        if ($this->prospectRepository->existsByRegistrationNumber($registrationNumber)) {
            throw new ProspectAlreadyRegisteredException($registrationNumber->unwrap());
        }

        $prospect = new Prospect(
            new Organization($request->getOrganizationId()),
            $registrationNumber,
            $request->getName()
        );
        $this->prospectRepository->save($prospect);

        return new RegisterProspectResponse($prospect->getId());
    }
}
