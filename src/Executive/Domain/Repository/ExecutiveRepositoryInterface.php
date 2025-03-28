<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Repository;

use Candice\Executive\Domain\Entity\Executive;
use Candice\Executive\Domain\ValueObject\ExecutiveEmail;

interface ExecutiveRepositoryInterface
{
    public function insert(Executive $executive): void;

    public function existsByEmail(ExecutiveEmail $executiveEmail): bool;
}
