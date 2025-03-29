<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Provider;

use Candice\Onboarding\Domain\Entity\Administrator;
use Candice\Onboarding\Domain\Provider\AdministratorProviderInterface;
use Candice\Onboarding\Domain\ValueObject\AdministratorFullName;
use Candice\Onboarding\Domain\ValueObject\AdministratorId;

final readonly class ConsoleAdministratorProvider implements AdministratorProviderInterface
{
    private Administrator $administrator;
    
    public function __construct()
    {
        $this->administrator = new Administrator(
            new AdministratorId('Console'),
            new AdministratorFullName(
                'Console',
                'Console'
            )
        );
    }

    public function get(): Administrator
    {
        return $this->administrator;
    }
}
