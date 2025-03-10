<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Domain\Exception;

use Exception;

final class OrganizationNotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Organization with id <%s> not found', $id));
    }
}
