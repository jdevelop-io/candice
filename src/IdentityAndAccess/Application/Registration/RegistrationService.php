<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\Registration;

use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\Enum\Role;
use Candice\IdentityAndAccess\Domain\Exception\EmailAlreadyInUseException;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Candice\IdentityAndAccess\Domain\Service\PasswordHasherInterface;

final readonly class RegistrationService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher
    ) {
    }

    public function execute(RegistrationRequest $request): RegistrationResponse
    {
        $email = $request->getEmail();
        $plainPassword = $request->getPlainPassword();

        if ($this->userRepository->findByEmail($email) !== null) {
            throw new EmailAlreadyInUseException($email);
        }

        $password = null;
        if ($plainPassword !== null) {
            $password = $this->passwordHasher->hash($plainPassword);
        }

        $id = $this->userRepository->getNextId();
        $user = new User($id, $email, $password, [Role::USER]);
        $this->userRepository->save($user);

        return new RegistrationResponse($user->getId());
    }
}
