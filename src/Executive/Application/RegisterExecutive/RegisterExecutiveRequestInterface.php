<?php

declare(strict_types=1);

namespace Candice\Executive\Application\RegisterExecutive;

interface RegisterExecutiveRequestInterface
{
    public function getOrganizationId(): string;

    public function getExecutiveEmail(): string;

    public function getExecutiveFirstName(): string;

    public function getExecutiveLastName(): string;
}
