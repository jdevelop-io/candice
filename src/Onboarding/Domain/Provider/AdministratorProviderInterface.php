<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Provider;

use Candice\Onboarding\Domain\Entity\Administrator;

interface AdministratorProviderInterface
{
    public function get(): Administrator;
}
