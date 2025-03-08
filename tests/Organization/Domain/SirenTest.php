<?php

declare(strict_types=1);

namespace Candice\Tests\Organization\Domain;

use Candice\Organization\Domain\Exception\InvalidSirenException;
use Candice\Organization\Domain\ValueObject\Siren;
use PHPUnit\Framework\TestCase;

final class SirenTest extends TestCase
{
    public function testWithValidSiren(): void
    {
        $siren = new Siren('123456789');

        self::assertSame('123456789', $siren->unwrap());
    }

    public function testWithInvalidSiren(): void
    {
        $this->expectException(InvalidSirenException::class);

        new Siren('InvalidSiren');
    }
}
