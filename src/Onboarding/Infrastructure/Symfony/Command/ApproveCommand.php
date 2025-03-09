<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Command;

use Candice\Onboarding\Application\Approve\ApproveRequest;
use Candice\Onboarding\Application\Approve\ApproveService;
use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'onboarding:application:approve',
    description: 'Approve an application'
)]
class ApproveCommand extends Command
{
    private const string ARGUMENT_ID = 'id';

    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private ApproveService $approveService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARGUMENT_ID, InputArgument::REQUIRED, "The application's id to approve");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $id = $input->getArgument(self::ARGUMENT_ID);

        $response = $this->approveService->execute(new ApproveRequest($id));
        $application = $this->applicationRepository->findById($response->getId());

        $io->table(
            ['Id', 'User email', 'Organization Registration Number', 'Organization Name', 'Status'],
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

        $io->success('Application has been approved successfully');

        return Command::SUCCESS;
    }
}
