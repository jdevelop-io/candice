<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\IdGenerator;

use Candice\Contexts\AbsenceDeclaration\Domain\IdGenerator\AbsenceIdGeneratorInterface;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenceId;
use Override;

final class IncrementalAbsenceIdGenerator implements AbsenceIdGeneratorInterface
{
    private int $nextId = 1;

    #[Override]
    public function generate(): AbsenceId
    {
        return new AbsenceId((string)$this->nextId++);
    }
}
