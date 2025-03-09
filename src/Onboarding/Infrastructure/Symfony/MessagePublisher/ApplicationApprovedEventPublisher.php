<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\MessagePublisher;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Service\ApplicationApprovedEventPublisherInterface;
use Candice\Onboarding\Infrastructure\Symfony\Message\ApplicationApprovedMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class ApplicationApprovedEventPublisher implements ApplicationApprovedEventPublisherInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function publish(Application $application): void
    {
        $this->messageBus->dispatch(new ApplicationApprovedMessage($application->getId()));
    }
}
