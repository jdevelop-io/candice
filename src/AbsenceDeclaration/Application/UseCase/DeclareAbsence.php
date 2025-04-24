<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Application\UseCase;


use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsenteeNotFoundException;
use Candice\Contexts\AbsenceDeclaration\Domain\IdGenerator\AbsenceIdGeneratorInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\Repository\AbsenteeWriteRepositoryInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Candice\Contexts\Shared\Domain\Service\ClockInterface;

final readonly class DeclareAbsence
{
    public function __construct(
        private AbsenteeWriteRepositoryInterface $absenteeRepository,
        private AbsenceIdGeneratorInterface $absenceIdGenerator,
        private ClockInterface $clock,
    ) {
    }

    public function execute(DeclareAbsenceRequestInterface $request): DeclareAbsenceResponse
    {
        $absentee = $this->getAbsentee($request);

        $period = new AbsencePeriod($request->getStartDate(), $request->getEndDate());
        $id = $this->absenceIdGenerator->generate();
        $absence = $absentee->declareAbsence($id, $period, $this->clock);

        $this->absenteeRepository->save($absentee);

        return new DeclareAbsenceResponse(
            absenteeId: $absentee->getId()->getValue(),
            absenceId: $absence->getId()->getValue(),
        );
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
