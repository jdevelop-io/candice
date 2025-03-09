<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Registration;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Exception\ApplicationInPendingValidationException;
use Candice\Onboarding\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Onboarding\Domain\Exception\UserAlreadyRegisteredException;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Candice\Onboarding\Domain\Service\OrganizationServiceInterface;
use Candice\Onboarding\Domain\Service\UserServiceInterface;

final readonly class ApplyService
{
    public function __construct(
        private UserServiceInterface $userService,
        private OrganizationServiceInterface $organizationService,
        private ApplicationRepositoryInterface $applicationRepository
    ) {
    }

    public function execute(ApplyRequest $request): ApplyResponse
    {
        if ($this->applicationRepository->existsByOrganizationRegistrationNumber(
            $request->getOrganizationRegistrationNumber()
        )) {
            throw new ApplicationInPendingValidationException($request->getOrganizationRegistrationNumber());
        }

        if ($this->userService->existsByEmail($request->getUserEmail())) {
            throw new UserAlreadyRegisteredException($request->getUserEmail());
        }

        $this->organizationService->validateRegistrationNumber($request->getOrganizationRegistrationNumber());

        if ($this->organizationService->existsByRegistrationNumber(
            $request->getOrganizationRegistrationNumber()
        )) {
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

        return new ApplyResponse($application->getId());
    }
}
