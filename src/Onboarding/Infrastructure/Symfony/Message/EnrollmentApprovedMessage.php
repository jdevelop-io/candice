<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Message;

use Candice\Onboarding\Domain\Event\EnrollmentApprovedEvent;

final readonly class EnrollmentApprovedMessage
{
    public function __construct(
        public string $enrollmentId,
        public string $applicantEmail,
        public string $applicantFirstName,
        public string $applicantLastName,
        public string $applicantPosition,
        public string $organizationRegistrationNumberType,
        public string $organizationRegistrationNumber,
        public string $organizationName,
        public string $approvedAt
    ) {
    }

    public static function fromEvent(EnrollmentApprovedEvent $event): self
    {
        return new self(
            $event->getAggregateRootId()->unwrap(),
            $event->getApplicant()->getEmail()->unwrap(),
            $event->getApplicant()->getFullName()->getFirstName(),
            $event->getApplicant()->getFullName()->getLastName(),
            $event->getApplicant()->getPosition()->unwrap(),
            $event->getOrganization()->getRegistrationNumber()->getType(),
            $event->getOrganization()->getRegistrationNumber()->getValue(),
            $event->getOrganization()->getName()->unwrap(),
            $event->getApprovedAt()->format('Y-m-d H:i:s')
        );
    }
}
