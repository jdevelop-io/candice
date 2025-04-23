<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Application\UseCase;

interface DeclareAbsenceRequestInterface
{
    public function getAbsenteeId(): string;
}
