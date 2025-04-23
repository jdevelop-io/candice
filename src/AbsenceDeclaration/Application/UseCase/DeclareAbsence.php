<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Application\UseCase;


use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsenteeNotFoundException;
use Candice\Contexts\AbsenceDeclaration\Domain\Repository\AbsenteeRepositoryInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;

final readonly class DeclareAbsence
{
    public function __construct(private AbsenteeRepositoryInterface $absenteeRepository)
    {
    }

    public function execute(DeclareAbsenceRequestInterface $request): void
    {
        $absentee = $this->getAbsentee($request);
    }

    private function getAbsentee(DeclareAbsenceRequestInterface $request): Absentee
    {
        $absenteeId = new AbsenteeId($request->getAbsenteeId());
        $absentee = $this->absenteeRepository->findById($absenteeId);

        return $absentee ?? throw new AbsenteeNotFoundException($absenteeId);
    }
}
