<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\ValueObject;

enum EnrollmentStatus: string
{
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';
}
