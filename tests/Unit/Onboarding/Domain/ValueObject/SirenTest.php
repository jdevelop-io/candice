<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Onboarding\Domain\ValueObject;

use Candice\Onboarding\Domain\ValueObject\Siren;
use PHPUnit\Framework\TestCase;

/**
 * TODO: This test can be removed after SubmitEnrollmentService is implemented.
 */
final class SirenTest extends TestCase
{
    public function testType(): void
    {
        $siren = new Siren('938123072');

        $this->assertSame('siren', $siren->getType());
    }

    public function testValue(): void
    {
        $siren = new Siren('938123072');

        $this->assertSame('938123072', $siren->getValue());
    }
}
