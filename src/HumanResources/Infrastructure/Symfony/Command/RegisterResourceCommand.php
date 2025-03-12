<?php

declare(strict_types=1);

namespace Candice\HumanResources\Infrastructure\Symfony\Command;

use Candice\HumanResources\Application\RegisterResource\RegisterResourceRequest;
use Candice\HumanResources\Application\RegisterResource\RegisterResourceService;
use Candice\HumanResources\Domain\Repository\ResourceRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'hr:resource:register',
    description: 'Register a new resource'
)]
final class RegisterResourceCommand extends Command
{
    private const string OPTION_ORGANIZATION_ID = 'organization-id';
    private const string OPTION_FIRST_NAME = 'first-name';
    private const string OPTION_LAST_NAME = 'last-name';

    public function __construct(
        private readonly ResourceRepositoryInterface $resourceRepository,
        private readonly RegisterResourceService $registerResourceService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(self::OPTION_ORGANIZATION_ID, null, InputOption::VALUE_REQUIRED, "The organization's ID");
        $this->addOption(self::OPTION_FIRST_NAME, null, InputOption::VALUE_REQUIRED, "The resource's first name");
        $this->addOption(self::OPTION_LAST_NAME, null, InputOption::VALUE_REQUIRED, "The resource's last name");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $organizationId = $input->getOption(self::OPTION_ORGANIZATION_ID);
        $firstName = $input->getOption(self::OPTION_FIRST_NAME);
        $lastName = $input->getOption(self::OPTION_LAST_NAME);

        $request = new RegisterResourceRequest($organizationId, $firstName, $lastName);
        $response = $this->registerResourceService->execute($request);
        $resource = $this->resourceRepository->findById($response->getId());

        $io->table(
            ['ID', 'Organization ID', 'First Name', 'Last Name'],
            [
                [
                    $resource->getId(),
                    $resource->getOrganization()->getId(),
                    $resource->getFullName()->getFirstName(),
                    $resource->getFullName()->getLastName()
                ]
            ]
        );

        $io->success('Resource registered successfully!');

        return Command::SUCCESS;
    }
}
