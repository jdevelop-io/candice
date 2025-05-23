<?php

declare(strict_types=1);

namespace Candice\Contexts\Tests\Unit\AbsenceDeclaration\Application\UseCase;

use Candice\Contexts\AbsenceDeclaration\Application\UseCase\DeclareAbsence;
use Candice\Contexts\AbsenceDeclaration\Application\UseCase\DeclareAbsenceResponse;
use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absence;
use Candice\Contexts\AbsenceDeclaration\Domain\Entity\Absentee;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsencePeriodOverlapException;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\AbsenteeNotFoundException;
use Candice\Contexts\AbsenceDeclaration\Domain\Exception\InvalidAbsencePeriodException;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsencePeriod;
use Candice\Contexts\AbsenceDeclaration\Domain\ValueObject\AbsenteeId;
use Candice\Contexts\AbsenceDeclaration\Infrastructure\EventBus\InMemoryEventBus;
use Candice\Contexts\AbsenceDeclaration\Infrastructure\EventSourcing\InMemoryEventStore;
use Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\IdGenerator\IncrementalAbsenceIdGenerator;
use Candice\Contexts\AbsenceDeclaration\Infrastructure\Persistence\Repository\EventSourcedAbsenteeWriteRepository;
use Candice\Contexts\Shared\Infrastructure\Service\FrozenClock;
use DateTimeImmutable;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DeclareAbsenceTest extends TestCase
{
    private readonly FrozenClock $clock;
    private readonly IncrementalAbsenceIdGenerator $absenceIdGenerator;
    private readonly EventSourcedAbsenteeWriteRepository $absenteeRepository;
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
            'whitespace start date' => [
                'startDate' => '   ',
                'endDate' => '2025-04-23T00:00:00+04:00',
            ],
            'french start date format' => [
                'startDate' => '23/04/2025 00:00:00',
                'endDate' => '2025-04-23T00:00:00+04:00',
            ],
            'empty end date' => [
                'startDate' => '2025-04-23T00:00:00+04:00',
                'endDate' => '',
            ],
            'whitespace end date' => [
                'startDate' => '2025-04-23T00:00:00+04:00',
                'endDate' => '   ',
            ],
            'french end date format' => [
                'startDate' => '2025-04-23T00:00:00+04:00',
                'endDate' => '23/04/2025 00:00:00',
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
                startDate: '2025-04-23T00:00:00+00:00',
                endDate: '2025-04-24T00:00:00+00:00',
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
        $this->assertNotNull($absentee);

        $id = $this->absenceIdGenerator->generate();
        $absentee->declareAbsence($id, new AbsencePeriod($startDate, $endDate), $this->clock);

        $this->absenteeRepository->save($absentee);
    }

    public function testAbsenceMustBeDeclared(): void
    {
        $request = new DeclareAbsenceRequest(
            absenteeId: 'ExistingAbsenteeId',
            startDate: '2025-04-23T00:00:00+00:00',
            endDate: '2025-04-24T00:00:00+00:00',
        );
        $response = $this->declareAbsence->execute($request);

        $this->assertAbsenceIsDeclared($request, $response);
    }

    public function assertAbsenceIsDeclared(
        DeclareAbsenceRequest $request,
        DeclareAbsenceResponse $response,
    ): void {
        $absentee = $this->absenteeRepository->findByIdWithAbsencesInPeriod(
            new AbsenteeId($response->getAbsenteeId()),
            new AbsencePeriod(
                $request->getStartDate(),
                $request->getEndDate(),
            ),
        );

        $this->assertNotNull($absentee, message: "Absentee {$request->getAbsenteeId()} not found.");
        $this->assertEquals(
            $request->getAbsenteeId(),
            $absentee->getId()->getValue(),
            message: <<<MESSAGE
                The absentee ID {$response->getAbsenteeId()} received in the response does not match the
                absentee ID {$request->getAbsenteeId()} passed in the request.
            MESSAGE,
        );

        $absences = $absentee->getAbsences();

        $absence = array_find(
            $absences,
            static fn(Absence $absence) => $absence->getId()->getValue() === $response->getAbsenceId(),
        );
        $this->assertNotNull(
            $absence,
            message: "Absence {$response->getAbsenceId()} not found for absentee ID {$request->getAbsenteeId()}.",
        );
        $this->assertEquals(
            $response->getAbsenceId(),
            $absence->getId()->getValue(),
            message: <<<MESSAGE
                The absence ID {$response->getAbsenceId()} received in the response does not correspond to the
                absence ID {$absence->getId()->getValue()} found in the absentee's list of absences.
            MESSAGE,
        );
        $this->assertEquals(
            $request->getStartDate(),
            $absence->getPeriod()->getStartDate()->format(AbsencePeriod::FORMAT),
            message: <<<MESSAGE
                The absence #{$absence->getId()->getValue()} start date
                {$absence->getPeriod()->getStartDate()->format(AbsencePeriod::FORMAT)} found in the absentee's list
                of absences does not match the start date {$request->getStartDate()} passed in the request.
            MESSAGE,
        );
        $this->assertEquals(
            $request->getEndDate(),
            $absence->getPeriod()->getEndDate()->format(AbsencePeriod::FORMAT),
            message: <<<MESSAGE
                The absence #{$absence->getId()->getValue()} end date
                {$absence->getPeriod()->getEndDate()->format(AbsencePeriod::FORMAT)} found in the absentee's list
                of absences does not match the end date {$request->getEndDate()} passed in the request.
            MESSAGE,
        );
        $this->assertEquals(
            $this->clock->now()->format(AbsencePeriod::FORMAT),
            $absence->getDeclaredOn()->getValue()->format(AbsencePeriod::FORMAT),
            message: <<<MESSAGE
                The absence #{$absence->getId()->getValue()} declared on date
                {$absence->getDeclaredOn()->getValue()->format(AbsencePeriod::FORMAT)} found in the absentee's list
                of absences does not match the current date {$this->clock->now()->format(AbsencePeriod::FORMAT)}.
            MESSAGE,
        );
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $frozenDateTime = DateTimeImmutable::createFromFormat(
            'Y-m-d\TH:i:sP',
            '2025-04-23T00:00:00+00:00',
        );
        $this->clock = new FrozenClock($frozenDateTime);
        $this->absenteeRepository = new EventSourcedAbsenteeWriteRepository(
            new InMemoryEventStore(new InMemoryEventBus()),
        );
        $this->absenceIdGenerator = new IncrementalAbsenceIdGenerator();
        $this->declareAbsence = new DeclareAbsence($this->absenteeRepository, $this->absenceIdGenerator, $this->clock);

        $this->absenteeRepository->save(
            Absentee::create(new AbsenteeId('ExistingAbsenteeId'), $this->clock),
        );
    }
}
