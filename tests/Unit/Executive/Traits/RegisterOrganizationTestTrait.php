<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Application\RegisterOrganization\RegisterOrganizationResponse;
use Candice\Executive\Application\RegisterOrganization\RegisterOrganizationService;
use Candice\Executive\Domain\Event\OrganizationRegisteredEvent;
use Candice\Executive\Domain\Factory\OrganizationFactory;
use Candice\Executive\Domain\Service\OrganizationRegistrationService;
use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\Executive\Application\RegisterOrganization\RegisterOrganizationRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait RegisterOrganizationTestTrait
{
    use EventBusTestTrait;

    private RegisterOrganizationService $service;

    protected function setUpRegisterOrganizationService(): void
    {
        $this->service = new RegisterOrganizationService(
            $this->organizationRepository,
            new OrganizationRegistrationService(new OrganizationFactory()),
            $this->eventBus,
        );
    }

    protected function registerOrganization(
        string $organizationId,
        string $organizationName
    ): RegisterOrganizationResponse {
        $request = new RegisterOrganizationRequest($organizationId, $organizationName);

        return $this->service->execute($request);
    }

    /**
     * @param array{
     *     organizationName: string,
     * } $expected
     */
    protected function assertOrganizationRegistered(array $expected, string $organizationId): void
    {
        $organization = $this->organizationRepository->findById(new OrganizationId($organizationId));

        $this->assertNotNull($organization);
        $this->assertEquals($organizationId, $organization->getId()->unwrap());
        $this->assertEquals($expected['organizationName'], $organization->getName()->unwrap());
        $this->assertEventDispatchedCount(1);
        $this->assertOrganizationRegisteredEvent($organizationId, $expected['organizationName']);
    }

    private function assertOrganizationRegisteredEvent(string $organizationId, string $organizationName): void
    {
        $events = $this->eventStore->load(new OrganizationId($organizationId));
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof OrganizationRegisteredEvent);
        $this->assertInstanceOf(OrganizationRegisteredEvent::class, $event);
        $this->assertSame($organizationId, $event->getAggregateRootId()->unwrap());
        $this->assertSame($organizationName, $event->getName()->unwrap());
    }
}
