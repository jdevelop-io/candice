<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\MessageHandler;

use Candice\Onboarding\Domain\Repository\ApplicationRepositoryInterface;
use Candice\Onboarding\Infrastructure\Symfony\DTO\OrganizationDTO;
use Candice\Onboarding\Infrastructure\Symfony\DTO\UserDTO;
use Candice\Onboarding\Infrastructure\Symfony\Message\ApplicationApprovedMessage;
use Candice\Onboarding\Infrastructure\Symfony\Service\HttpOrganizationService;
use Candice\Onboarding\Infrastructure\Symfony\Service\HttpUserService;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ApplicationApprovedMessageHandler
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private HttpOrganizationService $httpOrganizationService,
        private HttpUserService $httpUserService
    ) {
    }

    public function __invoke(ApplicationApprovedMessage $message): void
    {
        $application = $this->applicationRepository->findById($message->getId());
        if ($application === null) {
            throw new RuntimeException('Application not found');
        }

        $user = new UserDTO($application->getUserEmail());
        $this->httpUserService->register($user);

        $organization = new OrganizationDTO(
            $application->getOrganizationRegistrationNumber(),
            $application->getOrganizationName()
        );
        $this->httpOrganizationService->register($organization);
    }
}
