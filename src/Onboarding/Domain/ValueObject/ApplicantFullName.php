<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

final readonly class ApplicantFullName
{
    private string $firstName;
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $this->normalizeFirstName($firstName);
        $this->lastName = $this->normalizeLastName($lastName);
    }

    private function normalizeFirstName(string $value): string
    {
        $parts = explode('-', strtolower($value));
        $parts = array_map('ucfirst', $parts);

        return implode('-', $parts);
    }

    private function normalizeLastName(string $value): string
    {
        return strtoupper($value);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
