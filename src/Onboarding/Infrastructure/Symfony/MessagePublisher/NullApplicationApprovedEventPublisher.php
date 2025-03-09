<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\MessagePublisher;

use Candice\Onboarding\Domain\Entity\Application;
use Candice\Onboarding\Domain\Service\ApplicationApprovedEventPublisherInterface;

final readonly class NullApplicationApprovedEventPublisher implements ApplicationApprovedEventPublisherInterface
{
    public function publish(Application $application): void
    {
    }
}
