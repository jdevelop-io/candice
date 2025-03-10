<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Repository;

use Candice\CustomerRelationship\Domain\Entity\Prospect;
use Candice\CustomerRelationship\Domain\ValueObject\RegistrationNumber;

interface ProspectRepositoryInterface
{
    public function findById(string $id): ?Prospect;

    public function existsByRegistrationNumber(RegistrationNumber $registrationNumber): bool;

    public function save(Prospect $prospect): void;
}
