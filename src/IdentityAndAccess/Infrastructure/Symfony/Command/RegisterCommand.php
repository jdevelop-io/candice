<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Command;

use Candice\IdentityAndAccess\Application\Registration\RegistrationRequest;
use Candice\IdentityAndAccess\Application\Registration\RegistrationService;
use Candice\IdentityAndAccess\Domain\Enum\Role;
use Candice\IdentityAndAccess\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'iam:user:register',
    description: 'Register a new user'
)]
class RegisterCommand extends Command
{
    private const string OPTION_EMAIL = 'email';
    private const string OPTION_PLAIN_PASSWORD = 'plain-password';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RegistrationService $registrationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::OPTION_EMAIL,
            null,
            InputOption::VALUE_REQUIRED,
            "The user's email"
        );
        $this->addOption(
            self::OPTION_PLAIN_PASSWORD,
            null,
            InputOption::VALUE_REQUIRED,
            "The user's plain password"
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption(self::OPTION_EMAIL);
        $plainPassword = $input->getOption(self::OPTION_PLAIN_PASSWORD);

        $response = $this->registrationService->execute(new RegistrationRequest($email, $plainPassword));
        $user = $this->userRepository->findById($response->getId());

        $io->table(
            ['ID', 'Email', 'Roles'],
            [
                [
                    $user->getId(),
                    $user->getEmail(),
                    implode(', ', array_map(fn(Role $role) => $role->value, $user->getRoles()))
                ]
            ]
        );

        $io->success('User registered successfully!');

        return Command::SUCCESS;
    }
}
