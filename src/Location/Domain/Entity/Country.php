<?php

declare(strict_types=1);

namespace Candice\Location\Domain\Entity;

use Candice\Location\Domain\ValueObject\CountryCode;
use Candice\Location\Domain\ValueObject\CountryName;

final readonly class Country
{
    public function __construct(private CountryCode $code, private CountryName $name)
    {
    }

    public static function register(CountryCode $code, CountryName $name): self
    {
        return new self($code, $name);
    }

    public function getCode(): CountryCode
    {
        return $this->code;
    }

    public function getName(): CountryName
    {
        return $this->name;
    }
}
