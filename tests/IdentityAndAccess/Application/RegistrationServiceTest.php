<?php

declare(strict_types=1);

namespace Candice\Tests\IdentityAndAccess\Application;

use Candice\IdentityAndAccess\Application\Registration\RegistrationRequest;
use Candice\IdentityAndAccess\Application\Registration\RegistrationService;
use Candice\IdentityAndAccess\Domain\Entity\User;
use Candice\IdentityAndAccess\Domain\Enum\Role;
use Candice\IdentityAndAccess\Domain\Exception\EmailAlreadyInUseException;
use Candice\IdentityAndAccess\Infrastructure\Repository\InMemoryUserRepository;
use Candice\IdentityAndAccess\Infrastructure\Service\NullPasswordHasher;
use PHPUnit\Framework\TestCase;

final class RegistrationServiceTest extends TestCase
{
    private readonly NullPasswordHasher $passwordHasher;
    private readonly InMemoryUserRepository $userRepository;
    private readonly RegistrationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordHasher = new NullPasswordHasher();
        $this->userRepository = new InMemoryUserRepository();
        $this->service = new RegistrationService($this->userRepository, $this->passwordHasher);
    }

    public function testEmailShouldBeUnique(): void
    {
        $email = 'john.doe@example.com';
        $plainPassword = 'p4sswOrd!';
        $this->createUser($email, $plainPassword);

        $this->expectException(EmailAlreadyInUseException::class);

        $this->service->execute(new RegistrationRequest($email, $plainPassword));
    }

    public function testUserShouldBeRegistered(): void
    {
        $email = 'john.doe@example.com';
        $plainPassword = 'p4sswOrd!';

        $response = $this->service->execute(new RegistrationRequest($email, $plainPassword));

        $user = $this->userRepository->findById($response->getId());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($plainPassword, $user->getPassword());
        $this->assertSame([Role::USER], $user->getRoles());
    }

    private function createUser(string $email, string $plainPassword): void
    {
        $id = $this->userRepository->getNextId();
        $password = $this->passwordHasher->hash($plainPassword);

        $user = new User($id, $email, $password, [Role::USER]);
        $this->userRepository->save($user);
    }
}
