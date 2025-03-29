<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Event;

use Candice\Onboarding\Domain\Entity\Administrator;
use Candice\Onboarding\Domain\Entity\Applicant;
use Candice\Onboarding\Domain\Entity\Organization;
use Candice\Onboarding\Domain\ValueObject\EnrollmentId;
use Candice\Onboarding\Domain\ValueObject\EnrollmentProcessingDateTime;
use Candice\Shared\Domain\Event\DomainEvent;
use Candice\Shared\Domain\ValueObject\AggregateRootId;

final class EnrollmentApprovedEvent extends DomainEvent
{
    public function __construct(
        private readonly EnrollmentId $enrollmentId,
        private readonly Applicant $applicant,
        private readonly Organization $organization,
        private readonly Administrator $approvedBy,
        private readonly EnrollmentProcessingDateTime $approvedAt,
    ) {
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->enrollmentId;
    }

    public function getApplicant(): Applicant
    {
        return $this->applicant;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getApprovedBy(): Administrator
    {
        return $this->approvedBy;
    }

    public function getApprovedAt(): EnrollmentProcessingDateTime
    {
        return $this->approvedAt;
    }
}
