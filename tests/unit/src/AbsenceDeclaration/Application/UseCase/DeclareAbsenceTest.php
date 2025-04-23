<?php

declare(strict_types=1);

namespace Candice\Contexts\Tests\Unit\AbsenceDeclaration\Application\UseCase;

use Candice\Contexts\AbsenceDeclaration\Application\UseCase\DeclareAbsence;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsenteeNotFoundException;
use Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\Repository\InMemory\InMemoryAbsenteeRepository;
use Override;
use PHPUnit\Framework\TestCase;

final class DeclareAbsenceTest extends TestCase
{
    private readonly DeclareAbsence $declareAbsence;

    public function testAbsenteeMustExist(): void
    {
        $this->expectException(AbsenteeNotFoundException::class);

        $this->declareAbsence->execute(
            new DeclareAbsenceRequest(
                absenteeId: 'InvalidAbsenteeId',
            ),
        );
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->declareAbsence = new DeclareAbsence(new InMemoryAbsenteeRepository());
    }
}
