<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Registration;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Exception\ApplicationInPendingValidationException;
use Candice\Onboarding\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Onboarding\Domain\Exception\UserAlreadyRegisteredException;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Candice\Onboarding\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Onboarding\Domain\Repository\UserRepositoryInterface;

final readonly class ApplyService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private OrganizationRepositoryInterface $organizationRepository,
        private ApplicationRepositoryInterface $applicationRepository
    ) {
    }

    public function execute(RegistrationRequest $request): RegistrationResponse
    {
        if ($this->applicationRepository->existsByOrganizationRegistrationNumber(
            $request->getOrganizationRegistrationNumber()
        )) {
            throw new ApplicationInPendingValidationException($request->getOrganizationRegistrationNumber());
        }

        if ($this->userRepository->existsByEmail($request->getUserEmail())) {
            throw new UserAlreadyRegisteredException($request->getUserEmail());
        }

        if ($this->organizationRepository->existsByRegistrationNumber($request->getOrganizationRegistrationNumber())) {
            throw new OrganizationAlreadyRegisteredException($request->getOrganizationRegistrationNumber());
        }

        $id = $this->applicationRepository->getNextId();
        $application = new Application(
            $id,
            $request->getUserEmail(),
            $request->getOrganizationRegistrationNumber(),
            $request->getOrganizationName()
        );
        $this->applicationRepository->save($application);

        return new RegistrationResponse($application->getId());
    }
}
