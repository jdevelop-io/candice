<?php

declare(strict_types=1);

namespace Candice\Tests\IdentityAndAccess\Application;

use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailRequest;
use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailService;
use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\Enum\Role;
use Candice\IdentityAndAccess\Domain\Exception\UserWithEmailNotFoundException;
use Candice\IdentityAndAccess\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

final class GetUserByEmailServiceTest extends TestCase
{
    private readonly InMemoryUserRepository $userRepository;
    private readonly GetUserByEmailService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new InMemoryUserRepository();
        $this->service = new GetUserByEmailService($this->userRepository);
    }

    public function testUserDoesNotExists(): void
    {
        $this->expectException(UserWithEmailNotFoundException::class);

        $this->service->execute(new GetUserByEmailRequest('john.doe@example.com'));
    }

    public function testUserExists(): void
    {
        $email = 'john.doe@example.com';

        $user = $this->createUser($email);

        $response = $this->service->execute(new GetUserByEmailRequest($email));

        $this->assertSame($user->getId(), $response->getId());
        $this->assertSame($user->getEmail(), $response->getEmail());
    }

    private function createUser(string $email): User
    {
        $user = new User($this->userRepository->getNextId(), $email, 'p4sswOrd!', [Role::USER]);

        $this->userRepository->save($user);

        return $user;
    }
}
