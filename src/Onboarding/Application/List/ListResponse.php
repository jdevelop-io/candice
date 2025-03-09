<?php

declare(strict_types=1);

namespace Candice\Onboarding\Application\List;

final readonly class ListResponse
{
    /**
     * @param ApplicationDTO[] $applications
     */
    public function __construct(private array $applications)
    {
    }

    /**
     * @return ApplicationDTO[]
     */
    public function getApplications(): array
    {
        return $this->applications;
    }
}
