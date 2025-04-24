<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\Repository;

use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Repository\AbsenteeWriteRepositoryInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Candice\Contexts\Shared\Domain\Event\EventStoreInterface;
use Override;

final readonly class EventSourcedAbsenteeWriteRepository implements AbsenteeWriteRepositoryInterface
{
    public function __construct(private EventStoreInterface $eventStore)
    {
    }

    #[Override]
    public function findByIdWithAbsencesInPeriod(AbsenteeId $absenteeId, AbsencePeriod $period): ?Absentee
    {
        $events = $this->eventStore->load($absenteeId);
        if (empty($events)) {
            return null;
        }

        $absentee = new Absentee();
        foreach ($events as $event) {
            $absentee->apply($event);
        }

        return $absentee;
    }

    public function save(Absentee $absentee): void
    {
        $events = $absentee->releaseEvents();
        $this->eventStore->append($events);
    }
}
