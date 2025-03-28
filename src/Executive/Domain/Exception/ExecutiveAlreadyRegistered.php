<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Exception;

use Candice\Executive\Domain\ValueObject\ExecutiveEmail;
use DomainException;

final class ExecutiveAlreadyRegistered extends DomainException
{
    public function __construct(ExecutiveEmail $executiveEmail)
    {
        parent::__construct("Executive with email $executiveEmail already registered");
    }
}
