<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Traits;

use Candice\Onboarding\Infrastructure\Repository\InMemoryEnrollmentRepository;

trait SetupRepositoriesTestTrait
{
    private InMemoryEnrollmentRepository $enrollmentRepository;

    public function setUpRepositories(): void
    {
        $this->enrollmentRepository = new InMemoryEnrollmentRepository();
    }
}
