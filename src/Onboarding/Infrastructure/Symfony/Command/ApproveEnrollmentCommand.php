<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Command;

use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentRequest;
use Candice\Onboarding\Application\ApproveEnrollment\ApproveEnrollmentService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'onboarding:enrollment:approve',
    description: 'Approve an enrollment',
)]
class ApproveEnrollmentCommand extends Command
{
    private const string ENROLLMENT_ID = 'enrollment-id';

    public function __construct(private readonly ApproveEnrollmentService $approveEnrollmentService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(self::ENROLLMENT_ID, null, InputOption::VALUE_REQUIRED,'The ID of the enrollment to approve');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $enrollmentId = $input->getOption(self::ENROLLMENT_ID);

        $request = new ApproveEnrollmentRequest($enrollmentId);
        $this->approveEnrollmentService->execute($request);

        $io->success("Enrollment $enrollmentId approved successfully!");

        return Command::SUCCESS;
    }
}
