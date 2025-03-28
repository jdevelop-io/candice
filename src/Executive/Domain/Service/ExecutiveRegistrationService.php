<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Service;

use Candice\Executive\Domain\Entity\Executive;
use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\Factory\ExecutiveFactory;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use Candice\Executive\Domain\ValueObject\ExecutiveFullName;

final readonly class ExecutiveRegistrationService
{
    public function __construct(private ExecutiveFactory $executiveFactory)
    {
    }

    public function register(
        Organization $organization,
        string $email,
        string $firstName,
        string $lastName
    ): Executive {
        return $this->executiveFactory->register(
            $organization,
            new ExecutiveEmail($email),
            new ExecutiveFullName($firstName, $lastName),
        );
    }
}
