<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Enum;

enum ApplicationStatus: string
{
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';
}
