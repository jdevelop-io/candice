<?php

declare(strict_types=1);

namespace Candice\HumanResources\Domain\Exception;

use Exception;

final class OrganizationNotFoundException extends Exception
{
    public function __construct(string $organizationId)
    {
        parent::__construct(sprintf('Organization with id <%s> not found', $organizationId));
    }
}
