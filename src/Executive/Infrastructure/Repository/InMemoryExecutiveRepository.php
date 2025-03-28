<?php

declare(strict_types=1);

namespace Candice\Executive\Infrastructure\Repository;

use Candice\Executive\Domain\Entity\Executive;
use Candice\Executive\Domain\Repository\ExecutiveRepositoryInterface;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;

final class InMemoryExecutiveRepository implements ExecutiveRepositoryInterface
{
    /**
     * @var array<string, Executive>
     */
    private array $executiveByEmail = [];

    public function insert(Executive $executive): void
    {
        $this->executiveByEmail[$executive->getEmail()->unwrap()] = $executive;
    }

    public function findByEmail(ExecutiveEmail $email): ?Executive
    {
        return $this->executiveByEmail[$email->unwrap()] ?? null;
    }

    public function existsByEmail(ExecutiveEmail $executiveEmail): bool
    {
        return isset($this->executiveByEmail[$executiveEmail->unwrap()]);
    }
}
