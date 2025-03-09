<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Service;

use Candice\Onboarding\Domain\Entity\Application;

interface ApplicationApprovedEventPublisherInterface
{
    public function publish(Application $application): void;
}
