<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Command;

use Candice\Onboarding\Application\Apply\ApplyRequest;
use Candice\Onboarding\Application\Apply\ApplyService;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'onboarding:application:apply',
    description: 'Apply for onboarding'
)]
final class ApplyCommand extends Command
{
    private const string OPTION_USER_EMAIL = 'user-email';
    private const string OPTION_ORGANIZATION_REGISTRATION_NUMBER = 'organization-registration-number';
    private const string OPTION_ORGANIZATION_NAME = 'organization-name';

    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly ApplyService $applyService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(self::OPTION_USER_EMAIL, null, InputOption::VALUE_REQUIRED, "The user's email");
        $this->addOption(
            self::OPTION_ORGANIZATION_REGISTRATION_NUMBER,
            null,
            InputOption::VALUE_REQUIRED,
            "The organization's registration number"
        );
        $this->addOption(self::OPTION_ORGANIZATION_NAME, null, InputOption::VALUE_REQUIRED, "The organization's name");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userEmail = $input->getOption(self::OPTION_USER_EMAIL);
        $organizationRegistrationNumber = $input->getOption(self::OPTION_ORGANIZATION_REGISTRATION_NUMBER);
        $organizationName = $input->getOption(self::OPTION_ORGANIZATION_NAME);

        $response = $this->applyService->execute(
            new ApplyRequest($userEmail, $organizationRegistrationNumber, $organizationName)
        );
        $application = $this->applicationRepository->findById($response->getApplicationId());

        $io->table(
            ['ID', 'User email', 'Organization registration number', 'Organization name', 'Status'],
            [
                [
                    $application->getId(),
                    $application->getUserEmail(),
                    $application->getOrganizationRegistrationNumber(),
                    $application->getOrganizationName(),
                    $application->getStatus()->value
                ]
            ]
        );

        return Command::SUCCESS;
    }
}
