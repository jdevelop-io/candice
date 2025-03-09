<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Controller;

use Candice\Onboarding\Application\Approve\ApproveRequest;
use Candice\Onboarding\Application\Approve\ApproveService;
use Candice\Onboarding\Infrastructure\Symfony\Message\ApplicationApprovedMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

final class ValidationController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly ApproveService $approveService
    ) {
    }

    public function approve(string $applicationId): JsonResponse
    {
        $response = $this->approveService->execute(new ApproveRequest($applicationId));

        $this->messageBus->dispatch(new ApplicationApprovedMessage($applicationId));

        return new JsonResponse([
            'id' => $response->getId(),
            'status' => $response->getStatus(),
        ]);
    }
}
