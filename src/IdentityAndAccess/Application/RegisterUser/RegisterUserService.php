<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\RegisterUser;

use Candice\IdentityAndAccess\Domain\Exception\UserAlreadyRegisteredException;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Candice\IdentityAndAccess\Domain\Service\UserRegistrationService;
use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;
use Candice\Shared\Domain\Event\EventBusInterface;

final readonly class RegisterUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserRegistrationService $userRegistrationService,
        private EventBusInterface $eventBus
    ) {
    }

    public function execute(RegisterUserRequestInterface $request): RegisterUserResponse
    {
        $userEmail = new UserEmail($request->getUserEmail());

        if ($this->userRepository->existsByEmail($userEmail)) {
            throw new UserAlreadyRegisteredException($userEmail);
        }

        $user = $this->userRegistrationService->register(
            $request->getUserEmail(),
            $request->getUserFirstName(),
            $request->getUserLastName()
        );

        $events = $user->releaseEvents();
        $this->userRepository->insert($user);
        $this->eventBus->publish($events);

        return new RegisterUserResponse($user->getEmail()->unwrap());
    }
}
