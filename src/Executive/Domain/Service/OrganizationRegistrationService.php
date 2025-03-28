<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Service;

use Candice\Executive\Domain\Entity\Organization;
use Candice\Executive\Domain\Factory\OrganizationFactory;
use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Executive\Domain\ValueObject\OrganizationName;

final readonly class OrganizationRegistrationService
{
    public function __construct(private OrganizationFactory $organizationFactory)
    {
    }

    public function register(string $id, string $name): Organization
    {
        return $this->organizationFactory->register(new OrganizationId($id), new OrganizationName($name));
    }
}
