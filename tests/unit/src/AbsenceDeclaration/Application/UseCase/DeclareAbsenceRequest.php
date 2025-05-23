<?php

declare(strict_types=1);

namespace Candice\Contexts\Tests\Unit\AbsenceDeclaration\Application\UseCase;

use Candice\Contexts\AbsenceDeclaration\Application\UseCase\DeclareAbsenceRequestInterface;
use Override;

final readonly class DeclareAbsenceRequest implements DeclareAbsenceRequestInterface
{
    public function __construct(private string $absenteeId, private string $startDate, private string $endDate)
    {
    }

    #[Override]
    public function getAbsenteeId(): string
    {
        return $this->absenteeId;
    }

    #[Override]
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    #[Override]
    public function getEndDate(): string
    {
        return $this->endDate;
    }
}
