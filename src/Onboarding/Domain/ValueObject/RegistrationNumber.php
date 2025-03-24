<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

abstract class RegistrationNumber
{
    public function __construct(private string $type, private string $value)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
