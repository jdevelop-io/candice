<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Command;

use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailRequest;
use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'iam:user:get',
    description: 'Get a user',
)]
final class GetUserCommand extends Command
{
    private const string OPTION_EMAIL = 'email';

    public function __construct(
        private GetUserByEmailService $getUserByEmailService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(self::OPTION_EMAIL, null, InputOption::VALUE_REQUIRED, "The user's email");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption(self::OPTION_EMAIL);

        $response = $this->getUserByEmailService->execute(new GetUserByEmailRequest($email));

        $io->table(
            ['ID', 'Email'],
            [[$response->getId(), $response->getEmail()]]
        );

        $io->success('User was retrieved successfully!');

        return Command::SUCCESS;
    }
}
