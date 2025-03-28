<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterExecutive;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\Exception\ExecutiveAlreadyRegistered;
use Candice\Executive\Domain\Exception\OrganizationNotFoundException;
use Candice\Executive\Domain\Repository\ExecutiveRepositoryInterface;
use Candice\Executive\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Executive\Domain\Service\ExecutiveRegistrationService;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Shared\Domain\Event\EventBusInterface;

final readonly class RegisterExecutiveService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private ExecutiveRepositoryInterface $executiveRepository,
        private ExecutiveRegistrationService $executiveRegistrationService,
        private EventBusInterface $eventBus,
    ) {
    }

    public function execute(RegisterExecutiveRequestInterface $request): RegisterExecutiveResponse
    {
        $organization = $this->getOrganization($request->getOrganizationId());

        $this->guardAgainstExecutiveAlreadyRegistered($request->getExecutiveEmail());

        $executive = $this->executiveRegistrationService->register(
            $organization,
            $request->getExecutiveEmail(),
            $request->getExecutiveFirstName(),
            $request->getExecutiveLastName()
        );

        $events = $executive->releaseEvents();
        $this->executiveRepository->insert($executive);
        $this->eventBus->publish($events);

        return new RegisterExecutiveResponse($executive->getEmail()->unwrap());
    }

    private function getOrganization(string $organizationId): Organization
    {
        $organizationId = new OrganizationId($organizationId);

        $organization = $this->organizationRepository->findById($organizationId);

        return $organization ?? throw new OrganizationNotFoundException($organizationId);
    }

    private function guardAgainstExecutiveAlreadyRegistered(string $executiveEmail): void
    {
        $executiveEmail = new ExecutiveEmail($executiveEmail);

        if (!$this->executiveRepository->existsByEmail($executiveEmail)) {
            return;
        }

        throw new ExecutiveAlreadyRegistered($executiveEmail);
    }
}
