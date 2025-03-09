<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Controller;

use Candice\Onboarding\Application\Registration\ApplyRequest;
use Candice\Onboarding\Application\Registration\ApplyService;
use Candice\Onboarding\Domain\Exception\ApplicationInPendingValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApplicationController extends AbstractController
{
    public function __construct(private readonly ApplyService $applyService)
    {
    }

    public function apply(#[MapRequestPayload] ApplyRequest $request): JsonResponse
    {
        try {
            $response = $this->applyService->execute($request);

            return new JsonResponse([
                'applicationId' => $response->getApplicationId(),
            ]);
        } catch (ApplicationInPendingValidationException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }
}
