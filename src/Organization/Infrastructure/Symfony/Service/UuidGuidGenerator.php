<?php

declare(strict_types=1);

namespace Candice\Organization\Infrastructure\Symfony\Service;

use Symfony\Component\Uid\Uuid;

final readonly class UuidGuidGenerator implements GuidGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::v4()->toString();
    }
}
