<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Factory;

use Candice\Executive\Domain\Entity\Executive;
use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use Candice\Executive\Domain\ValueObject\ExecutiveFullName;

final readonly class ExecutiveFactory
{
    public function create(
        Organization $organization,
        string $executiveEmail,
        string $executiveFirstName,
        string $executiveLastName
    ): Executive {
        return new Executive(
            new ExecutiveEmail($executiveEmail),
            new ExecutiveFullName($executiveFirstName, $executiveLastName),
            $organization,
        );
    }
}
