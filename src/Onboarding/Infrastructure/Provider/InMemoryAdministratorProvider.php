<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Provider;

use Candice\Onboarding\Domain\Entity\Administrator;
use Candice\Onboarding\Domain\Provider\AdministratorProviderInterface;
use RuntimeException;

final class InMemoryAdministratorProvider implements AdministratorProviderInterface
{
    private ?Administrator $administrator = null;

    public function define(Administrator $administrator): void
    {
        $this->administrator = $administrator;
    }

    public function get(): Administrator
    {
        if ($this->administrator === null) {
            throw new RuntimeException('Administrator not defined.');
        }

        return $this->administrator;
    }
}
