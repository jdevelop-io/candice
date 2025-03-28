<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterOrganization;

use Candice\Executive\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Executive\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Executive\Domain\Service\OrganizationRegistrationService;
use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Shared\Domain\Event\EventBusInterface;

final readonly class RegisterOrganizationService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private OrganizationRegistrationService $organizationRegistrationService,
        private EventBusInterface $eventBus
    ) {
    }

    public function execute(RegisterOrganizationRequestInterface $request): RegisterOrganizationResponse
    {
        $this->guardAgainstDuplicateOrganization($request->getOrganizationId());

        $organization = $this->organizationRegistrationService->register(
            $request->getOrganizationId(),
            $request->getOrganizationName()
        );

        $events = $organization->releaseEvents();
        $this->organizationRepository->insert($organization);
        $this->eventBus->publish($events);

        return new RegisterOrganizationResponse($organization->getId()->unwrap());
    }

    private function guardAgainstDuplicateOrganization(string $organizationId): void
    {
        $organizationId = new OrganizationId($organizationId);

        if (!$this->organizationRepository->existsById($organizationId)) {
            return;
        }

        throw new OrganizationAlreadyRegisteredException($organizationId);
    }
}
