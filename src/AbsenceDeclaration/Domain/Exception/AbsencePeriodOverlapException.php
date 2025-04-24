<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Exception;

use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absence;
use Candice\Contexts\Shared\Domain\Exception\DomainException;

final class AbsencePeriodOverlapException extends DomainException
{
    public function __construct(
        Absence $newAbsence,
        Absence $existingAbsence,
    ) {
        parent::__construct(
            "The absence period {$newAbsence->getPeriod()} overlaps with an existing absence period {$existingAbsence->getPeriod()}.",
        );
    }
}
