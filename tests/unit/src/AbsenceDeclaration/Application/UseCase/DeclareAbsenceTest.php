<?php

declare(strict_types=1);

namespace Candice\Contexts\Tests\Unit\AbsenceDeclaration\Application\UseCase;

use Candice\Contexts\AbsenceDeclaration\Application\UseCase\DeclareAbsence;
use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absence;
use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsencePeriodOverlapException;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsenteeNotFoundException;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\InvalidAbsencePeriodException;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\Repository\InMemory\InMemoryAbsenteeRepository;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DeclareAbsenceTest extends TestCase
{
    private readonly InMemoryAbsenteeRepository $absenteeRepository;
    private readonly DeclareAbsence $declareAbsence;

    /**
     * @return array<string, array{startDate: string, endDate: string}>
     */
    public static function invalidAbsencePeriodProvider(): array
    {
        return [
            'empty start date' => [
                'startDate' => '',
                'endDate' => '2025-04-23T00:00:00+04:00',
            ],
            'empty end date' => [
                'startDate' => '2025-04-23T00:00:00+04:00',
                'endDate' => '',
            ],
            'whitespace start date' => [
                'startDate' => '   ',
                'endDate' => '2025-04-23T00:00:00+04:00',
            ],
            'whitespace end date' => [
                'startDate' => '2025-04-23T00:00:00+04:00',
                'endDate' => '   ',
            ],
            'start date after end date' => [
                'startDate' => '2025-04-24T00:00:00+04:00',
                'endDate' => '2025-04-23T00:00:00+04:00',
            ],
        ];
    }

    public function testAbsenteeMustExist(): void
    {
        $this->expectException(AbsenteeNotFoundException::class);

        $this->declareAbsence->execute(
            new DeclareAbsenceRequest(
                absenteeId: 'InvalidAbsenteeId',
                startDate: '2025-04-23T00:00:00+04:00',
                endDate: '2025-04-24T00:00:00+04:00',
            ),
        );
    }

    #[DataProvider('invalidAbsencePeriodProvider')]
    public function testAbsencePeriodMustBeValid(string $startDate, string $endDate): void
    {
        $this->expectException(InvalidAbsencePeriodException::class);

        $this->declareAbsence->execute(
            new DeclareAbsenceRequest(
                absenteeId: 'ExistingAbsenteeId',
                startDate: $startDate,
                endDate: $endDate,
            ),
        );
    }

    public function testAbsenceMustNotOverlapWithExistingAbsence(): void
    {
        $this->createAbsence(
            id: 'ExistingAbsenteeId',
            startDate: '2025-04-23T12:00:00+00:00',
            endDate: '2025-04-24T12:00:00+00:00',
        );

        $this->expectException(AbsencePeriodOverlapException::class);

        $this->declareAbsence->execute(
            new DeclareAbsenceRequest(
                absenteeId: 'ExistingAbsenteeId',
                startDate: '2025-04-23T00:00:00+00:00',
                endDate: '2025-04-24T00:00:00+00:00',
            ),
        );
    }

    private function createAbsence(string $id, string $startDate, string $endDate): void
    {
        $absentee = $this->absenteeRepository->findByIdWithAbsencesInPeriod(
            new AbsenteeId($id),
            new AbsencePeriod($startDate, $endDate),
        );
        $absence = new Absence(new AbsencePeriod($startDate, $endDate));
        $absentee->declareAbsence($absence);

        $this->absenteeRepository->save($absentee);
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->absenteeRepository = new InMemoryAbsenteeRepository();
        $this->declareAbsence = new DeclareAbsence($this->absenteeRepository);

        $this->absenteeRepository->save(
            new Absentee(new AbsenteeId('ExistingAbsenteeId')),
        );
    }
}
