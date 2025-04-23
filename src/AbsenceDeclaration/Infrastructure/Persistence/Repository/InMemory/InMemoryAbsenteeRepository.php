<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\Repository\InMemory;

use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Repository\AbsenteeRepositoryInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Override;

final class InMemoryAbsenteeRepository implements AbsenteeRepositoryInterface
{
    /**
     * @var array<string, Absentee>
     */
    private array $absenteeById = [];

    #[Override]
    public function findById(AbsenteeId $absenteeId): ?Absentee
    {
        return $this->absenteeById[$absenteeId->getValue()] ?? null;
    }
}
