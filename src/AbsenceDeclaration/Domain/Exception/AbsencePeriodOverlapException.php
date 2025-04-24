<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Exception;

use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absence;
use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\Shared\Domain\Exception\DomainException;

final class AbsencePeriodOverlapException extends DomainException
{
    public function __construct(
        Absentee $absentee,
        Absence $existingAbsence,
        AbsencePeriod $overlappingPeriod,
    ) {
        parent::__construct(
            sprintf(
                'Absence period %s overlaps with existing absence %s for absentee %s',
                $overlappingPeriod,
                $existingAbsence->getId(),
                $absentee->getId(),
            ),
        );
    }
}
