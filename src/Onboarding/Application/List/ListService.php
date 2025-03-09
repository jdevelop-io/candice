<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\List;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Enum\ApplicationStatus;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;

final readonly class ListService
{
    public function __construct(private ApplicationRepositoryInterface $applicationRepository)
    {
    }

    public function execute(ListRequest $request): ListResponse
    {
        $applications = $this->getApplications($request);

        return new ListResponse(
            array_map(
                fn(Application $application) => $this->toDTO($application),
                $applications
            )
        );
    }

    private function toDTO(Application $application): ApplicationDTO
    {
        return new ApplicationDTO(
            $application->getId(),
            $application->getUserEmail(),
            $application->getOrganizationRegistrationNumber(),
            $application->getOrganizationName(),
            $application->getStatus()->value
        );
    }

    /**
     * @param ListRequest $request
     * @return Application[]
     */
    private function getApplications(ListRequest $request): array
    {
        if ($request->getStatus()) {
            return $this->applicationRepository->findAllByStatus(ApplicationStatus::from($request->getStatus()));
        }

        return $this->applicationRepository->findAll();
    }
}
