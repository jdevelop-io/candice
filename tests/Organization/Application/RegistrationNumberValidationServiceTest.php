<?php

declare(strict_types=1);

namespace Candice\Tests\Organization\Application;

use Candice\Organization\Application\RegistrationNumberValidation\RegistrationNumberValidationRequest;
use Candice\Organization\Application\RegistrationNumberValidation\RegistrationNumberValidationService;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;
use PHPUnit\Framework\TestCase;

final class RegistrationNumberValidationServiceTest extends TestCase
{
    private RegistrationNumberValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new RegistrationNumberValidationService(new RegistrationNumberFactory());
    }

    public function testRegistrationNumberIsInvalid(): void
    {
        $response = $this->service->execute(new RegistrationNumberValidationRequest('InvalidRegistrationNumber'));

        $this->assertFalse($response->isValid());
        $this->assertSame('Registration number <InvalidRegistrationNumber> is invalid', $response->getReason());
    }

    public function testRegistrationNumberIsValid(): void
    {
        $response = $this->service->execute(new RegistrationNumberValidationRequest('123456789'));

        $this->assertTrue($response->isValid());
        $this->assertNull($response->getReason());
    }
}
