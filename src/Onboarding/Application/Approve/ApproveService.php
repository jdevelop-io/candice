<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Approve;

use Candice\Onboarding\Domain\Exception\ApplicationNotFoundException;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Candice\Onboarding\Domain\Service\ApplicationApprovedEventPublisherInterface;

final readonly class ApproveService
{
    public function __construct(
        private ApplicationApprovedEventPublisherInterface $applicationApprovedEventPublisher,
        private ApplicationRepositoryInterface $applicationRepository
    ) {
    }

    public function execute(ApproveRequest $request): ApproveResponse
    {
        $application = $this->applicationRepository->findById($request->getId());

        if ($application === null) {
            throw new ApplicationNotFoundException($request->getId());
        }

        $application->approve();

        $this->applicationRepository->save($application);

        $this->applicationApprovedEventPublisher->publish($application);

        return new ApproveResponse($application->getId(), $application->getStatus()->value);
    }
}
