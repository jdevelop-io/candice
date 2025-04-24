<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Entity;

use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenceId;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\DeclaredOn;

final readonly class Absence
{
    public function __construct(private AbsenceId $id, private AbsencePeriod $period, private DeclaredOn $declaredOn)
    {
    }

    public function getPeriod(): AbsencePeriod
    {
        return $this->period;
    }

    public function getDeclaredOn(): DeclaredOn
    {
        return $this->declaredOn;
    }

    public function getId(): AbsenceId
    {
        return $this->id;
    }
}
