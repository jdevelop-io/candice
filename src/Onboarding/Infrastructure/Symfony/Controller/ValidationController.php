<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Controller;

use Candice\Onboarding\Application\Approve\ApproveRequest;
use Candice\Onboarding\Application\Approve\ApproveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ValidationController extends AbstractController
{
    public function __construct(
        private readonly ApproveService $approveService
    ) {
    }

    public function approve(string $applicationId): JsonResponse
    {
        $response = $this->approveService->execute(new ApproveRequest($applicationId));

        return new JsonResponse([
            'id' => $response->getId(),
            'status' => $response->getStatus(),
        ]);
    }
}
