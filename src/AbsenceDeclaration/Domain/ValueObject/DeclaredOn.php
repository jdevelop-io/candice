<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\ValueObject;

use DateTimeInterface;

final readonly class DeclaredOn
{
    public function __construct(
        private DateTimeInterface $value,
    ) {
    }

    public function getValue(): DateTimeInterface
    {
        return $this->value;
    }
}
