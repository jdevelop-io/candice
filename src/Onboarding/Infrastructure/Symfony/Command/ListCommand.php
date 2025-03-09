<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Command;

use Candice\Onboarding\Application\List\ApplicationDTO;
use Candice\Onboarding\Application\List\ListRequest;
use Candice\Onboarding\Application\List\ListService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'onboarding:application:list',
    description: 'List all applications'
)]
class ListCommand extends Command
{
    private const string OPTION_STATUS = 'status';

    public function __construct(private readonly ListService $listApplicationsService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::OPTION_STATUS,
            null,
            InputOption::VALUE_OPTIONAL,
            'List only applications based on status'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $status = $input->getOption(self::OPTION_STATUS);
        $response = $this->listApplicationsService->execute(new ListRequest($status));

        $io->table(
            ['ID', 'User email', 'Organization registration number', 'Organization name', 'Status'],
            array_map(
                fn(ApplicationDTO $application) => [
                    $application->getId(),
                    $application->getUserEmail(),
                    $application->getOrganizationRegistrationNumber(),
                    $application->getOrganizationName(),
                    $application->getStatus()
                ],
                $response->getApplications()
            )
        );

        return Command::SUCCESS;
    }
}
