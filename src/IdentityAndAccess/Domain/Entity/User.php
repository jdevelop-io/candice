<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\Entity;

use Candice\IdentityAndAccess\Domain\Event\UserRegisteredEvent;
use Candice\IdentityAndAccess\Domain\ValueObject\UserEmail;
use Candice\IdentityAndAccess\Domain\ValueObject\UserFullName;
use Candice\Shared\Domain\Event\DomainEventPublisherTrait;

final class User
{
    use DomainEventPublisherTrait;

    public function __construct(private readonly UserEmail $email, private readonly UserFullName $fullName)
    {
    }

    public static function register(UserEmail $email, UserFullName $fullName): self
    {
        $user = new self($email, $fullName);

        $user->record(new UserRegisteredEvent($email, $fullName));

        return $user;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getFullName(): UserFullName
    {
        return $this->fullName;
    }
}
