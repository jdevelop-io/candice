<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Application\GetUserByEmail;

use Candice\IdentityAndAccess\Domain\Exception\UserWithEmailNotFoundException;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;

final readonly class GetUserByEmailService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(GetUserByEmailRequest $request): GetUserByEmailResponse
    {
        $user = $this->userRepository->findByEmail($request->getEmail());

        if ($user === null) {
            throw new UserWithEmailNotFoundException($request->getEmail());
        }

        return new GetUserByEmailResponse($user->getId(), $user->getEmail());
    }
}
