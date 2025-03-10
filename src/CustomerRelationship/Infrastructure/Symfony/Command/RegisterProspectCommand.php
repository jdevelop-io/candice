<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Infrastructure\Symfony\Command;

use Candice\CustomerRelationship\Application\RegisterProspect\RegisterProspectRequest;
use Candice\CustomerRelationship\Application\RegisterProspect\RegisterProspectService;
use Candice\CustomerRelationship\Domain\Repository\ProspectRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'crm:prospect:register',
    description: 'Register a new prospect'
)]
class RegisterProspectCommand extends Command
{
    private const string OPTION_ORGANIZATION_ID = 'organization-id';
    private const string OPTION_REGISTRATION_NUMBER = 'registration-number';
    private const string OPTION_NAME = 'name';

    public function __construct(
        private readonly ProspectRepositoryInterface $prospectRepository,
        private readonly RegisterProspectService $registerProspectService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(self::OPTION_ORGANIZATION_ID, null, InputOption::VALUE_REQUIRED, "The organization's ID");
        $this->addOption(
            self::OPTION_REGISTRATION_NUMBER,
            null,
            InputOption::VALUE_REQUIRED,
            "The prospect's registration number"
        );
        $this->addOption(self::OPTION_NAME, null, InputOption::VALUE_REQUIRED, "The prospect's name");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $organizationId = $input->getOption(self::OPTION_ORGANIZATION_ID);
        $registrationNumber = $input->getOption(self::OPTION_REGISTRATION_NUMBER);
        $name = $input->getOption(self::OPTION_NAME);

        $response = $this->registerProspectService->execute(
            new RegisterProspectRequest(
                $organizationId,
                $registrationNumber,
                $name
            )
        );
        $prospect = $this->prospectRepository->findById($response->getId());

        $io->table(
            ['ID', 'Organization ID', 'Registration Number', 'Name'],
            [
                [
                    $prospect->getId(),
                    $prospect->getOrganization()->getId(),
                    $prospect->getRegistrationNumber()->unwrap(),
                    $prospect->getName()
                ]
            ]
        );

        $io->success('Prospect registered successfully!');

        return Command::SUCCESS;
    }
}
