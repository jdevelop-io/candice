<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\ValueObject;

final readonly class AbsenceId
{
    public function __construct(
        private string $value,
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
