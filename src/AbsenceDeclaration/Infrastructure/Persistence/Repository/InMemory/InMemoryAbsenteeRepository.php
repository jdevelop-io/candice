<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\Repository\InMemory;

use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absence;
use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Repository\AbsenteeRepositoryInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Override;

final class InMemoryAbsenteeRepository implements AbsenteeRepositoryInterface
{
    /**
     * @var list<non-empty-string>
     */
    private array $absenteeIds = [];

    /**
     * @var array<string, Absence[]>
     */
    private array $absencesByAbsenteeId = [];

    #[Override]
    public function findByIdWithAbsencesInPeriod(AbsenteeId $absenteeId, AbsencePeriod $period): ?Absentee
    {
        if (!$this->existsById($absenteeId)) {
            return null;
        }

        $absences = array_filter(
            $this->absencesByAbsenteeId[$absenteeId->getValue()] ?? [],
            static fn(Absence $absence) => $absence->getPeriod()->overlaps($period),
        );

        return new Absentee(
            id: $absenteeId,
            absences: $absences,
        );
    }

    private function existsById(AbsenteeId $absenteeId): bool
    {
        return in_array($absenteeId->getValue(), $this->absenteeIds, true);
    }

    public function save(Absentee $absentee): void
    {
        if (!$this->existsById($absentee->getId())) {
            $this->absenteeIds[] = $absentee->getId()->getValue();
        }

        $this->absencesByAbsenteeId[$absentee->getId()->getValue()] = $absentee->getAbsences();
    }
}
