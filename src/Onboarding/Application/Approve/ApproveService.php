<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Approve;

use Candice\Onboarding\Domain\Exception\ApplicationNotFoundException;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;

final readonly class ApproveService
{
    public function __construct(private ApplicationRepositoryInterface $applicationRepository)
    {
    }

    public function execute(ApproveRequest $request): ApproveResponse
    {
        $application = $this->applicationRepository->findById($request->getId());

        if ($application === null) {
            throw new ApplicationNotFoundException($request->getId());
        }

        $application->approve();

        $this->applicationRepository->save($application);

        return new ApproveResponse($application->getId());
    }
}
