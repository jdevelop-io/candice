<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Repository;

use Candice\Organization\Domain\Entity\Organization;
use Candice\Organization\Domain\ValueObject\RegistrationNumber;

interface OrganizationRepositoryInterface
{
    public function existsByRegistrationNumber(RegistrationNumber $registrationNumber): bool;

    public function findByRegistrationNumber(RegistrationNumber $registrationNumber): ?Organization;

    public function save(Organization $organization): void;
}
