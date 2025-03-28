<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Provider;

use Candice\Onboarding\Domain\Entity\Administrator;
use Candice\Onboarding\Domain\Provider\AdministratorProviderInterface;

final class InMemoryAdministratorProvider implements AdministratorProviderInterface
{
    private Administrator $administrator;

    public function define(Administrator $administrator): void
    {
        $this->administrator = $administrator;
    }

    public function get(): Administrator
    {
        return $this->administrator;
    }
}
