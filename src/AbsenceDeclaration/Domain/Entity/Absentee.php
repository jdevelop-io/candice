<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Entity;

use Candice\Contexts\AbsenceDeclaration\Domain\Event\AbsenceDeclaredEvent;
use Candice\Contexts\AbsenceDeclaration\Domain\Event\AbsenteeCreatedEvent;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsencePeriodOverlapException;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenceId;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\DeclaredOn;
use Candice\Contexts\Shared\Domain\Entity\EventSourcedAggregateRootTrait;
use Candice\Contexts\Shared\Domain\Service\ClockInterface;
use Candice\Contexts\Shared\Domain\ValueObject\OccurredOn;

final class Absentee
{
    use EventSourcedAggregateRootTrait;

    private AbsenteeId $id;

    /**
     * @var list<Absence>
     */
    private array $absences;

    public static function create(AbsenteeId $id, ClockInterface $clock): self
    {
        $absentee = new self();
        $absentee->recordThat(new AbsenteeCreatedEvent($id, new OccurredOn($clock->now())));

        return $absentee;
    }

    public function getId(): AbsenteeId
    {
        return $this->id;
    }

    public function declareAbsence(AbsenceId $id, AbsencePeriod $period, ClockInterface $clock): Absence
    {
        $absenceOverlaps = $this->findAbsenceOverlaps($period);
        if ($absenceOverlaps) {
            throw new AbsencePeriodOverlapException($this, $absenceOverlaps, $period);
        }

        return $this->recordThat(new AbsenceDeclaredEvent($this->id, new OccurredOn($clock->now()), $id, $period));
    }

    public function findAbsenceOverlaps(AbsencePeriod $period): ?Absence
    {
        return array_find(
            $this->absences,
            static fn(Absence $absence) => $absence->getPeriod()->overlaps($period),
        );
    }

    /**
     * @return list<Absence>
     */
    public function getAbsences(): array
    {
        return $this->absences;
    }

    protected function applyAbsenteeCreatedEvent(AbsenteeCreatedEvent $event)
    {
        $this->id = $event->getAggregateRootId();
        $this->absences = [];
    }

    protected function applyAbsenceDeclaredEvent(AbsenceDeclaredEvent $event): Absence
    {
        $absence = new Absence(
            $event->getAbsenceId(),
            $event->getPeriod(),
            new DeclaredOn($event->getOccurredOn()->getValue()),
        );

        $this->id = $event->getAggregateRootId();
        $this->absences[] = $absence;

        return $absence;
    }
}
