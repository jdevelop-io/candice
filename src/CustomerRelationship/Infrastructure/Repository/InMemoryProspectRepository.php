<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Infrastructure\Repository;

use Candice\CustomerRelationship\Domain\Entity\Prospect;
use Candice\CustomerRelationship\Domain\Repository\ProspectRepositoryInterface;
use Candice\CustomerRelationship\Domain\ValueObject\RegistrationNumber;

final class InMemoryProspectRepository implements ProspectRepositoryInterface
{
    /**
     * @var array<string, Prospect>
     */
    private array $prospectById = [];

    /**
     * @var array<string, Prospect>
     */
    private array $prospectByRegistrationNumber = [];

    /**
     * @var int
     */
    private int $nextId = 1;

    public function findById(string $id): ?Prospect
    {
        return $this->prospectById[$id] ?? null;
    }

    public function existsByRegistrationNumber(RegistrationNumber $registrationNumber): bool
    {
        return isset($this->prospectByRegistrationNumber[$registrationNumber->unwrap()]);
    }

    public function save(Prospect $prospect): void
    {
        if ($prospect->getId() === null) {
            $prospect->setId((string)$this->nextId++);
        }

        $this->prospectById[$prospect->getId()] = $prospect;
        $this->prospectByRegistrationNumber[$prospect->getRegistrationNumber()->unwrap()] = $prospect;
    }
}
