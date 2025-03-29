<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\EventListener;

use Candice\Onboarding\Domain\Event\EnrollmentApprovedEvent;
use Candice\Onboarding\Infrastructure\Symfony\Message\EnrollmentApprovedMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener]
final readonly class EnrollmentApprovedEventListener
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(EnrollmentApprovedEvent $event): void
    {
        $this->messageBus->dispatch(EnrollmentApprovedMessage::fromEvent($event));
    }
}
