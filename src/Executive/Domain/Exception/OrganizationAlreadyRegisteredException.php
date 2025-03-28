<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Exception;

use Candice\Executive\Domain\ValueObject\OrganizationId;
use DomainException;

final class OrganizationAlreadyRegisteredException extends DomainException
{
    public function __construct(OrganizationId $organizationId)
    {
        parent::__construct("Organization $organizationId is already registered");
    }
}
