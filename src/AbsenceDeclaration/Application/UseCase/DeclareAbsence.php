<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Application\UseCase;


use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absence;
use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsenteeNotFoundException;
use Candice\Contexts\AbsenceDeclaration\Domain\Repository\AbsenteeRepositoryInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;

final readonly class DeclareAbsence
{
    public function __construct(private AbsenteeRepositoryInterface $absenteeRepository)
    {
    }

    public function execute(DeclareAbsenceRequestInterface $request): void
    {
        $absentee = $this->getAbsentee($request);
        $period = new AbsencePeriod($request->getStartDate(), $request->getEndDate());

        $absence = new Absence($period);
        $absentee->declareAbsence($absence);
    }

    private function getAbsentee(DeclareAbsenceRequestInterface $request): Absentee
    {
        $absenteeId = new AbsenteeId($request->getAbsenteeId());
        $period = new AbsencePeriod($request->getStartDate(), $request->getEndDate());
        $absentee = $this->absenteeRepository->findByIdWithAbsencesInPeriod(
            $absenteeId,
            $period,
        );

        return $absentee ?? throw new AbsenteeNotFoundException($absenteeId);
    }
}
