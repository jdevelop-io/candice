<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Exception;

final class ApplicationNotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Application with id <%s> not found', $id));
    }
}
