<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding;

use Candice\Onboarding\Domain\Factory\RegistrationNumberFactory;
use Candice\Onboarding\Infrastructure\Repository\InMemoryEnrollmentRepository;
use PHPUnit\Framework\TestCase;

abstract class EnrollmentTest extends TestCase
{
    protected InMemoryEnrollmentRepository $enrollmentRepository;
    protected RegistrationNumberFactory $registrationNumberFactory;

    protected function setUp(): void
    {
        $this->enrollmentRepository = new InMemoryEnrollmentRepository();
        $this->registrationNumberFactory = new RegistrationNumberFactory();
    }
}
