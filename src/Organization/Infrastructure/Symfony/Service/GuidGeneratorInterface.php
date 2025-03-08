<?php

declare(strict_types=1);

namespace Candice\Organization\Infrastructure\Symfony\Service;

interface GuidGeneratorInterface
{
    public function generate(): string;
}
