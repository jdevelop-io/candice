<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Repository;

use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;

interface AbsenteeRepositoryInterface
{
    public function findByIdWithAbsencesInPeriod(AbsenteeId $absenteeId, AbsencePeriod $period): ?Absentee;
}
