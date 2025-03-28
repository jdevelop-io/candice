<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Domain\ValueObject;


use Candice\IdentityAndAccess\Domain\Exception\InvalidUserEmailException;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class UserEmail extends AggregateRootId
{
    public function __construct(string $value)
    {
        $this->validate($value);

        parent::__construct($value);
    }

    private function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidUserEmailException($value);
        }
    }
}
