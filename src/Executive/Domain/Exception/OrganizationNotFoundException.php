<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Exception;

use Candice\Executive\Domain\ValueObject\OrganizationId;
use DomainException;

final class OrganizationNotFoundException extends DomainException
{
    public function __construct(OrganizationId $organizationId)
    {
        parent::__construct("Organization $organizationId could not be found");
    }
}
