<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Entity;

use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use Candice\Executive\Domain\ValueObject\ExecutiveFullName;

final readonly class Executive
{
    public function __construct(private ExecutiveEmail $email, private ExecutiveFullName $fullName, private Organization $organization)
    {
    }

    public function getEmail(): ExecutiveEmail
    {
        return $this->email;
    }

    public function getFullName(): ExecutiveFullName
    {
        return $this->fullName;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }
}
