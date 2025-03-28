<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\IdentityAndAccess\Traits;

use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserResponse;
use Candice\IdentityAndAccess\Application\RegisterUser\RegisterUserService;
use Candice\IdentityAndAccess\Domain\Event\UserRegisteredEvent;
use Candice\IdentityAndAccess\Domain\Factory\UserFactory;
use Candice\IdentityAndAccess\Domain\Service\UserRegistrationService;
use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Tests\Unit\IdentityAndAccess\Application\RegisterUser\RegisterUserRequest;
use Candice\Tests\Unit\Shared\Traits\EventBusTestTrait;

trait RegisterUserTestTrait
{
    use EventBusTestTrait;

    private RegisterUserService $service;

    protected function setUpRegisterUserService(): void
    {
        $this->service = new RegisterUserService(
            $this->userRepository,
            new UserRegistrationService(new UserFactory()),
            $this->eventBus
        );
    }

    protected function registerUser(string $userEmail, string $userFirstName, string $userLastName): RegisterUserResponse
    {
        $request = new RegisterUserRequest(
            $userEmail,
            $userFirstName,
            $userLastName
        );

        return $this->service->execute($request);
    }

    /**
     * @param array{
     *     userFirstName: string,
     *     userLastName: string,
     * } $expected
     */
    protected function assertUserRegistered(array $expected, string $userEmail): void
    {
        $user = $this->userRepository->findByEmail($userEmail);

        $this->assertNotNull($user);
        $this->assertSame(
            $expected['userFirstName'],
            $user->getFullName()->getFirstName()
        );
        $this->assertSame(
            $expected['userLastName'],
            $user->getFullName()->getLastName()
        );

        $this->assertEventDispatchedCount(1);
        $this->assertUserRegisteredEvent($userEmail, $expected['userFirstName'], $expected['userLastName']);
    }

    protected function assertUserRegisteredEvent(string $userEmail, string $userFirstName, string $userLastName): void
    {
        $events = $this->eventStore->load(new UserEmail($userEmail));
        $event = $events->find(static fn(DomainEvent $event) => $event instanceof UserRegisteredEvent);
        $this->assertInstanceOf(UserRegisteredEvent::class, $event);
        $this->assertSame($userEmail, $event->getAggregateRootId()->unwrap());
        $this->assertSame($userFirstName, $event->getFullName()->getFirstName());
        $this->assertSame($userLastName, $event->getFullName()->getLastName());
    }
}
