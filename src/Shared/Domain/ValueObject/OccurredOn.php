<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Domain\ValueObject;

use DateTimeInterface;

final readonly class OccurredOn
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
