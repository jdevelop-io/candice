<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterOrganization;

interface RegisterOrganizationRequestInterface
{
    public function getOrganizationId(): string;

    public function getOrganizationName(): string;
}
