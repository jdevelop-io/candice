<?php

declare(strict_types=1);

namespace Candice\Contexts\AbsenceDeclaration\Domain\Exception;

use Candice\Contexts\Shared\Domain\Exception\DomainException;

final class InvalidAbsencePeriodException extends DomainException
{
    public static function emptyStartDate(): self
    {
        return new self('The start date cannot be empty.');
    }

    public static function invalidStartDateFormat(string $startDate, string $format): self
    {
        return new self(
            "The start date {$startDate} is not in the expected format {$format}.",
        );
    }

    public static function emptyEndDate(): self
    {
        return new self('The end date cannot be empty.');
    }

    public static function invalidEndDateFormat(string $endDate, string $format): self
    {
        return new self(
            "The end date {$endDate} is not in the expected format {$format}.",
        );
    }

    public static function startDateAfterEndDate(string $startDate, string $endDate): self
    {
        return new self(
            "The start date {$startDate} cannot be after the end date {$endDate}.",
        );
    }
}
