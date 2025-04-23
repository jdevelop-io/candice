<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\ValueObject;

final readonly class AbsenteeId
{
    public function __construct(private string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
