<?php

declare(strict_types=1);

namespace Candice\Contexts\Tests\Unit\HelloWorld;

use Candice\Contexts\HelloWorld\HelloWorld;
use PHPUnit\Framework\TestCase;

final class HelloWorldTest extends TestCase
{
    public function testPrint(): void
    {
        $helloWorld = new HelloWorld();

        $this->assertSame('Hello World', $helloWorld->print());
    }
}
