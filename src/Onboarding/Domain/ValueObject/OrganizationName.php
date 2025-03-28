<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

use Stringable;

final readonly class OrganizationName implements Stringable
{
    public function __construct(private string $value)
    {
    }

    public function unwrap(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
