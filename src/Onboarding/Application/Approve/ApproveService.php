<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\Approve;

use Candice\Onboarding\Domain\Event\ApplicationApprovedEvent;
use Candice\Onboarding\Domain\Exception\ApplicationNotFoundException;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Candice\Shared\Domain\Event\EventDispatcherInterface;

final readonly class ApproveService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
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

        $this->eventDispatcher->dispatch(new ApplicationApprovedEvent($application->getId()));

        return new ApproveResponse($application->getId(), $application->getStatus()->value);
    }
}
