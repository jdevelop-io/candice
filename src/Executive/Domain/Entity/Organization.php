<?php

declare(strict_types=1);

namespace Candice\Executive\Domain\Entity;

use Candice\Executive\Domain\Event\OrganizationRegisteredEvent;
use Candice\Executive\Domain\ValueObject\OrganizationId;
use Candice\Executive\Domain\ValueObject\OrganizationName;
use Candice\Shared\Domain\Event\DomainEventPublisherTrait;

final class Organization
{
    use DomainEventPublisherTrait;

    public function __construct(private OrganizationId $id, private OrganizationName $name)
    {
    }

    public static function register(OrganizationId $id, OrganizationName $name): self
    {
        $organization = new self($id, $name);

        $organization->record(new OrganizationRegisteredEvent($id, $name));

        return $organization;
    }

    public function getId(): OrganizationId
    {
        return $this->id;
    }

    public function getName(): OrganizationName
    {
        return $this->name;
    }
}
