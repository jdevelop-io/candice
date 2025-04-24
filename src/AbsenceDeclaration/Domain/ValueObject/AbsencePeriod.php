<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\ValueObject;

use Candice\Contexts\AbsenceDeclaration\Domain\Exception\InvalidAbsencePeriodException;
use Safe\DateTimeImmutable;
use Safe\Exceptions\DatetimeException;

final readonly class AbsencePeriod
{
    public const string FORMAT = 'Y-m-d\TH:i:sP';
    private DateTimeImmutable $startDate;
    private DateTimeImmutable $endDate;

    public function __construct(string $startDate, string $endDate)
    {
        [$startDate, $endDate] = $this->normalize($startDate, $endDate);

        $this->validate($startDate, $endDate);

        $this->startDate = $this->buildDateTime($startDate);
        $this->endDate = $this->buildDateTime($endDate);

        if ($this->startDate > $this->endDate) {
            throw InvalidAbsencePeriodException::startDateAfterEndDate($startDate, $endDate);
        }
    }

    private function normalize(string $startDate, string $endDate): array
    {
        return array_map('trim', [$startDate, $endDate]);
    }

    private function validate(string $startDate, string $endDate): void
    {
        $this->validateStartDate($startDate);
        $this->validateEndDate($endDate);
    }

    private function validateStartDate(string $startDate): void
    {
        if (empty($startDate)) {
            throw InvalidAbsencePeriodException::emptyStartDate();
        }

        try {
            $this->buildDateTime($startDate);
        } catch (DatetimeException) {
            throw InvalidAbsencePeriodException::invalidStartDateFormat($startDate, self::FORMAT);
        }
    }

    /**
     * @throws DatetimeException
     */
    private function buildDateTime(string $startDate): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(self::FORMAT, $startDate);
    }

    private function validateEndDate(string $endDate): void
    {
        if (empty($endDate)) {
            throw InvalidAbsencePeriodException::emptyEndDate();
        }

        try {
            $this->buildDateTime($endDate);
        } catch (DatetimeException) {
            throw InvalidAbsencePeriodException::invalidEndDateFormat($endDate, self::FORMAT);
        }
    }

    public function __toString(): string
    {
        return "{$this->startDate->format(self::FORMAT)} - {$this->endDate->format(self::FORMAT)}";
    }

    public function overlaps(AbsencePeriod $period): bool
    {
        return $this->startDate < $period->getEndDate() && $this->endDate > $period->getStartDate();
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }
}
