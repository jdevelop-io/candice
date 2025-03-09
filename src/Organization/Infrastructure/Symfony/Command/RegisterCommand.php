<?php

declare(strict_types=1);

namespace Candice\Organization\Infrastructure\Symfony\Command;

use Candice\Organization\Application\Register\RegistrationRequest;
use Candice\Organization\Application\Register\RegistrationService;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use Candice\Organization\Domain\Repository\OrganizationRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'organization:register',
    description: 'Register a new organization'
)]
class RegisterCommand extends Command
{
    private const string OPTION_REGISTRATION_NUMBER = 'registration-number';
    private const string OPTION_NAME = 'name';

    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly RegistrationNumberFactory $registrationNumberFactory,
        private readonly RegistrationService $registrationService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::OPTION_REGISTRATION_NUMBER,
            null,
            InputOption::VALUE_REQUIRED,
            "The organization's registration number"
        );
        $this->addOption(
            self::OPTION_NAME,
            null,
            InputOption::VALUE_REQUIRED,
            "The organization's name"
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $registrationNumber = $input->getOption(self::OPTION_REGISTRATION_NUMBER);
        $name = $input->getOption(self::OPTION_NAME);

        $response = $this->registrationService->execute(new RegistrationRequest($registrationNumber, $name));
        $organization = $this->organizationRepository->findByRegistrationNumber(
            $this->registrationNumberFactory->create($response->getRegistrationNumber())
        );

        $io->table(
            ['Registration Number', 'Name'],
            [[$organization->getRegistrationNumber()->unwrap(), $organization->getName()]]
        );

        $io->success('Company registered successfully!');

        return Command::SUCCESS;
    }
}
