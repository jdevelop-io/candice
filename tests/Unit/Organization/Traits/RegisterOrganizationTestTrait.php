<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization\Traits;

use Candice\Organization\Application\RegisterOrganization\RegisterOrganizationResponse;
use Candice\Organization\Application\RegisterOrganization\RegisterOrganizationService;
use Candice\Organization\Domain\Event\OrganizationRegisteredEvent;
use Candice\Organization\Domain\Factory\OrganizationFactory;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\Service\OrganizationRegistrationService;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\Organization\Application\RegisterOrganization\RegisterOrganizationRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait RegisterOrganizationTestTrait
{
    use EventBusTestTrait;

    private RegisterOrganizationService $service;

    protected function setUpRegisterOrganizationService(): void
    {
        $this->service = new RegisterOrganizationService(
            $this->organizationRepository,
            new OrganizationRegistrationService(new OrganizationFactory(), new RegistrationNumberFactory()),
            $this->eventBus,
        );
    }

    protected function registerOrganization(
        string $registrationNumberType,
        string $registrationNumber,
        string $name
    ): RegisterOrganizationResponse {
        $request = new RegisterOrganizationRequest(
            $registrationNumberType,
            $registrationNumber,
            $name
        );

        return $this->service->execute($request);
    }

    /**
     * @param array{
     *     organizationName: string,
     * } $expected
     */
    protected function assertOrganizationRegistered(
        array $expected,
        string $organizationRegistrationNumberType,
        string $organizationRegistrationNumber
    ): void {
        $registrationNumber = $this->registrationNumberFactory->create(
            $organizationRegistrationNumberType,
            $organizationRegistrationNumber
        );
        $organization = $this->organizationRepository->findByRegistrationNumber($registrationNumber);

        $this->assertNotNull($organization);
        $this->assertSame($organizationRegistrationNumberType, $organization->getRegistrationNumber()->getType());
        $this->assertSame($organizationRegistrationNumber, $organization->getRegistrationNumber()->getValue());
        $this->assertSame($expected['organizationName'], $organization->getName()->unwrap());
        $this->assertEventDispatchedCount(1);
        $this->assertOrganizationRegisteredEvent(
            $organization->getRegistrationNumber()->getType(),
            $organization->getRegistrationNumber()->getValue(),
            $organization->getName()->unwrap(),
        );
    }

    private function assertOrganizationRegisteredEvent(
        string $registrationNumberType,
        string $registrationNumberValue,
        string $name
    ): void {
        $registrationNumber = $this->registrationNumberFactory->create(
            $registrationNumberType,
            $registrationNumberValue
        );
        $events = $this->eventStore->load($registrationNumber);
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof OrganizationRegisteredEvent);
        $this->assertInstanceOf(OrganizationRegisteredEvent::class, $event);
        $this->assertSame($registrationNumberValue, $event->getAggregateRootId()->unwrap());
        $this->assertSame($name, $event->getName()->unwrap());
    }
}
