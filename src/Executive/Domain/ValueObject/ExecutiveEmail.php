<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\ValueObject;

use Candice\Executive\Domain\Exception\InvalidExecutiveEmailException;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class ExecutiveEmail extends AggregateRootId
{
    public function __construct(string $value)
    {
        $this->validate($value);

        parent::__construct($value);
    }

    private function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidExecutiveEmailException($value);
        }
    }
}
