<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Entity;

use Candice\Onboarding\Domain\ValueObject\AdministratorFullName;
use Candice\Onboarding\Domain\ValueObject\AdministratorId;

final readonly class Administrator
{
    public function __construct(private AdministratorId $id, private AdministratorFullName $fullName)
    {
    }

    public function getId(): AdministratorId
    {
        return $this->id;
    }

    public function getFullName(): AdministratorFullName
    {
        return $this->fullName;
    }
}
