<?php

declare(strict_types=1);

namespace Candice\Shared\Infrastructure\Symfony\Repository;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserRepositoryInterface
{

    public function findByEmail(string $email): ?UserInterface;
}
