<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Executive\Traits;

use Candice\Executive\Application\RegisterExecutive\RegisterExecutiveResponse;
use Candice\Executive\Application\RegisterExecutive\RegisterExecutiveService;
use Candice\Executive\Domain\Event\ExecutiveRegisteredEvent;
use Candice\Executive\Domain\Service\ExecutiveRegistrationService;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\Executive\Application\RegisterExecutive\RegisterExecutiveRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait RegisterExecutiveTestTrait
{
    use EventBusTestTrait;

    private RegisterExecutiveService $service;

    protected function setUpRegisterExecutiveService(): void
    {
        $this->service = new RegisterExecutiveService(
            $this->organizationRepository,
            $this->executiveRepository,
            new ExecutiveRegistrationService($this->executiveFactory),
            $this->eventBus,
        );
    }

    protected function registerExecutive(
        string $organizationId,
        string $executiveEmail,
        string $executiveFirstName,
        string $executiveLastName
    ): RegisterExecutiveResponse {
        $request = new RegisterExecutiveRequest(
            $organizationId,
            $executiveEmail,
            $executiveFirstName,
            $executiveLastName
        );

        return $this->service->execute($request);
    }

    /**
     * @param array{
     *     organizationId: string,
     *     organizationName: string,
     *     executiveFirstName: string,
     *     executiveLastName: string
     * } $expected
     */
    protected function assertExecutiveRegistered(array $expected, string $executiveEmail): void
    {
        $executive = $this->executiveRepository->findByEmail(new ExecutiveEmail($executiveEmail));

        $this->assertNotNull($executive);
        $this->assertEquals($expected['organizationId'], $executive->getOrganization()->getId()->unwrap());
        $this->assertEquals($expected['organizationName'], $executive->getOrganization()->getName()->unwrap());
        $this->assertEquals($executiveEmail, $executive->getEmail()->unwrap());
        $this->assertEquals($expected['executiveFirstName'], $executive->getFullName()->getFirstName());
        $this->assertEquals($expected['executiveLastName'], $executive->getFullName()->getLastName());

        $this->assertEventDispatchedCount(1);
        $this->assertExecutiveRegisteredEvent(
            [
                'executiveFirstName' => $expected['executiveFirstName'],
                'executiveLastName' => $expected['executiveLastName'],
                'organizationId' => $expected['organizationId'],
                'organizationName' => $expected['organizationName'],
            ],
            $executiveEmail
        );
    }

    /**
     * @param array{
     *     executiveFirstName: string,
     *     executiveLastName: string,
     *     organizationId: string,
     *     organizationName: string
     * } $expected
     */
    protected function assertExecutiveRegisteredEvent(array $expected, string $executiveEmail): void
    {
        $events = $this->eventStore->load(new ExecutiveEmail($executiveEmail));
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof ExecutiveRegisteredEvent);
        $this->assertInstanceOf(ExecutiveRegisteredEvent::class, $event);
        $this->assertSame($executiveEmail, $event->getAggregateRootId()->unwrap());
        $this->assertSame($expected['executiveFirstName'], $event->getFullName()->getFirstName());
        $this->assertSame($expected['executiveLastName'], $event->getFullName()->getLastName());
        $this->assertSame($expected['organizationId'], $event->getOrganization()->getId()->unwrap());
        $this->assertSame($expected['organizationName'], $event->getOrganization()->getName()->unwrap());
    }
}
