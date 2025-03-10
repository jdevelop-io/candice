<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\EventListener;

use Candice\Onboarding\Domain\Event\ApplicationApprovedEvent;
use Candice\Onboarding\Infrastructure\Symfony\Message\ApplicationApprovedMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener]
final readonly class ApplicationApprovedEventListener
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(ApplicationApprovedEvent $event): void
    {
        $this->messageBus->dispatch(new ApplicationApprovedMessage($event->getId()));
    }
}
