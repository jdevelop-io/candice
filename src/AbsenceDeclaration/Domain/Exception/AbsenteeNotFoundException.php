<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Exception;

use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Candice\Contexts\Shared\Domain\Exception\DomainException;

final class AbsenteeNotFoundException extends DomainException
{
    public function __construct(AbsenteeId $absenteeId)
    {
        parent::__construct(
            "Absentee with ID {$absenteeId} not found.",
        );
    }
}
