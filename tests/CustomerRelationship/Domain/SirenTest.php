<?php

declare(strict_types=1);

namespace Candice\Tests\CustomerRelationship\Domain;

use Candice\CustomerRelationship\Domain\Exception\InvalidSirenException;
use Candice\CustomerRelationship\Domain\ValueObject\Siren;
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
