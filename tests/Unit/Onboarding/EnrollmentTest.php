<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding;

use Candice\Tests\Unit\Onboarding\Traits\SetupFactoriesTestTrait;
use Candice\Tests\Unit\Onboarding\Traits\SetupRepositoriesTestTrait;
use Candice\Tests\Unit\Onboarding\Traits\SubmitEnrollmentTestTrait;
use Candice\Tests\Unit\Shared\Traits\SetupEventBusTestTrait;
use PHPUnit\Framework\TestCase;

abstract class EnrollmentTest extends TestCase
{
    /** Setup traits */
    use SetupEventBusTestTrait;
    use SetupFactoriesTestTrait;
    use SetupRepositoriesTestTrait;

    /** Feature traits */
    use SubmitEnrollmentTestTrait;

    protected function setUp(): void
    {
        $this->setUpFactories();
        $this->setUpRepositories();
        $this->setUpEventBus();

        $this->setUpSubmitEnrollmentService();
    }
}
