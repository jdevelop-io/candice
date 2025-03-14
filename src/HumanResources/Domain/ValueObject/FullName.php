<?php

declare(strict_types=1);

namespace Candice\HumanResources\Domain\ValueObject;

final readonly class FullName
{
    private string $firstName;
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $this->normalizeFirstName($firstName);
        $this->lastName = $this->normalizeLastName($lastName);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    private function normalizeFirstName(string $value): string
    {
        $value = ucfirst(strtolower($value));

        // The next character after a '-' should be uppercase
        return preg_replace_callback('/-([a-z])/', function ($matches) {
            return '-' . strtoupper($matches[1]);
        }, $value);
    }

    private function normalizeLastName(string $value): string
    {
        return strtoupper($value);
    }
}
