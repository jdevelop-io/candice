<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Event;

use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenceId;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\Shared\Domain\Event\DomainEvent;
use Candice\Contexts\Shared\Domain\ValueObject\AggregateRootId;
use Candice\Contexts\Shared\Domain\ValueObject\OccurredOn;

final readonly class AbsenceDeclaredEvent extends DomainEvent
{
    public function __construct(
        AggregateRootId $aggregateRootId,
        OccurredOn $occurredOn,
        private AbsenceId $absenceId,
        private AbsencePeriod $period,
    ) {
        parent::__construct($aggregateRootId, $occurredOn);
    }

    public function getAbsenceId(): AbsenceId
    {
        return $this->absenceId;
    }

    public function getPeriod(): AbsencePeriod
    {
        return $this->period;
    }
}
