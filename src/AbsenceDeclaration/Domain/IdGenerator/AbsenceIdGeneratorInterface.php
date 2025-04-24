<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\IdGenerator;

use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenceId;

interface AbsenceIdGeneratorInterface
{
    public function generate(): AbsenceId;
}
