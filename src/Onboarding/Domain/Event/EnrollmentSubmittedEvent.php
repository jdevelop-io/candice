<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Event;

use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class EnrollmentSubmittedEvent extends DomainEvent
{
    public function __construct(private readonly EnrollmentId $enrollmentId)
    {
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->enrollmentId;
    }
}
