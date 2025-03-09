<?php

declare(strict_types=1);

namespace Candice\Onboarding\Domain\Exception;

use Exception;

final class ApplicationNotPendingApprovalException extends Exception
{
    public function __construct(string $status)
    {
        parent::__construct(sprintf('Application is not pending approval, current status is <%s>', $status));
    }
}
