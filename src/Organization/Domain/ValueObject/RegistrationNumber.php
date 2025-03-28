<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\ValueObject;

use Candice\Shared\Domain\ValueObject\AggregateRootId;

abstract class RegistrationNumber extends AggregateRootId
{
    public function __construct(private string $type, private string $value)
    {
        parent::__construct($value);
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
