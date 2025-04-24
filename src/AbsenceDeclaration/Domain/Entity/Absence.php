<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Entity;

use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenceId;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;

final readonly class Absence
{
    public function __construct(private AbsenceId $id, private AbsencePeriod $period)
    {
    }

    public function overlaps(Absence $existingAbsence): bool
    {
        return $this->period->overlaps($existingAbsence->getPeriod());
    }

    public function getPeriod(): AbsencePeriod
    {
        return $this->period;
    }

    public function getId(): AbsenceId
    {
        return $this->id;
    }
}
