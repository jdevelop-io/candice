<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegisterOrganization;

use Candice\Organization\Domain\Exception\OrganizationAlreadyRegisteredException;
use Candice\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Candice\Organization\Domain\Service\OrganizationRegistrationService;
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
        $registrationNumber = $this->organizationRegistrationService->createRegistrationNumber(
            $request->getOrganizationRegistrationNumberType(),
            $request->getOrganizationRegistrationNumber(),
        );

        if ($this->organizationRepository->existsByRegistrationNumber($registrationNumber)) {
            throw new OrganizationAlreadyRegisteredException($registrationNumber);
        }

        $organization = $this->organizationRegistrationService->register(
            $request->getOrganizationRegistrationNumberType(),
            $request->getOrganizationRegistrationNumber(),
            $request->getOrganizationName(),
        );

        $events = $organization->releaseEvents();
        $this->organizationRepository->insert($organization);
        $this->eventBus->publish($events);

        return new RegisterOrganizationResponse(
            $organization->getRegistrationNumber()->getType(),
            $organization->getRegistrationNumber()->getValue(),
        );
    }
}
