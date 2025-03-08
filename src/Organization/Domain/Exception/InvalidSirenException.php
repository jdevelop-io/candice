<?php

declare(strict_types=1);

namespace Candice\Organization\Domain\Exception;

use Exception;

final class InvalidSirenException extends Exception
{
    public function __construct(string $siren)
    {
        parent::__construct(sprintf('Invalid SIREN number <%s>. Must be a 9-digit number.', $siren));
    }
}
