<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Application\UseCase;

final readonly class DeclareAbsenceResponse
{
    public function __construct(
        private string $absenteeId,
        private string $absenceId,
    ) {
    }

    public function getAbsenteeId(): string
    {
        return $this->absenteeId;
    }

    public function getAbsenceId(): string
    {
        return $this->absenceId;
    }
}
