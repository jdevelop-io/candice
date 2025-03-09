<?php

declare(strict_types=1);

namespace Candice\Organization\Application\Register;

use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\Repository\OrganizationRepositoryInterface;

final readonly class RegistrationService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private RegistrationNumberFactory $registrationNumberFactory
    ) {
    }

    public function execute(RegistrationRequest $request): RegistrationResponse
    {
        $registrationNumber = $this->registrationNumberFactory->create($request->getRegistrationNumber());

        if ($this->organizationRepository->existsByRegistrationNumber($registrationNumber)) {
            throw new OrganizationAlreadyRegisteredException($registrationNumber->unwrap());
        }

        $id = $this->organizationRepository->getNextId();
        $organization = new Organization($id, $registrationNumber, $request->getName());
        $this->organizationRepository->save($organization);

        return new RegistrationResponse($registrationNumber->unwrap());
    }
}
