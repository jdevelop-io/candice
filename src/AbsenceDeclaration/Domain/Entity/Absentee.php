<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Entity;

use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsencePeriodOverlapException;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;

final class Absentee
{
    /**
     * @param list<Absence> $absences
     */
    public function __construct(private readonly AbsenteeId $id, private array $absences = [])
    {
    }

    public function getId(): AbsenteeId
    {
        return $this->id;
    }

    public function declareAbsence(Absence $absence): void
    {
        foreach ($this->absences as $existingAbsence) {
            if ($absence->overlaps($existingAbsence)) {
                throw new AbsencePeriodOverlapException($absence, $existingAbsence);
            }
        }

        $this->absences[] = $absence;
    }

    /**
     * @return list<Absence>
     */
    public function getAbsences(): array
    {
        return $this->absences;
    }
}
