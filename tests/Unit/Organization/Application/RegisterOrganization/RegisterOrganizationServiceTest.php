<?php

declare(strict_types=1);

namespace Candice\Tests\Unit\Organization\Application\RegisterOrganization;

use Candice\Organization\Domain\Exception\InvalidSirenChecksumException;
use Candice\Organization\Domain\Exception\InvalidSirenFormatException;
use Candice\Organization\Domain\Exception\UnsupportedRegistrationNumberTypeException;
use Candice\Tests\Unit\Organization\OrganizationTest;
use Candice\Tests\Unit\Organization\Traits\RegisterOrganizationTestTrait;

final class RegisterOrganizationServiceTest extends OrganizationTest
{
    use RegisterOrganizationTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRegisterOrganizationService();
    }

    public function testRegistrationNumberTypeShouldBeSiren(): void
    {
        $this->expectException(UnsupportedRegistrationNumberTypeException::class);

        $this->registerOrganization(
            'bn',
            '938123072',
            'Acme Inc.',
        );
    }

    public function testRegistrationNumberShouldContain9Digits(): void
    {
        $this->expectException(InvalidSirenFormatException::class);

        $this->registerOrganization(
            'siren',
            '93812307',
            'Acme Inc.',
        );
    }

    public function testRegistrationNumberShouldHaveValidChecksum(): void
    {
        $this->expectException(InvalidSirenChecksumException::class);

        $this->registerOrganization(
            'siren',
            '123456789',
            'Acme Inc.',
        );
    }
}
