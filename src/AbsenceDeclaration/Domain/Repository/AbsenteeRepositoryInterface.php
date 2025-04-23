<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Repository;

use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;

interface AbsenteeRepositoryInterface
{
    public function findById(AbsenteeId $absenteeId): ?Absentee;
}
