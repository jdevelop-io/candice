<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Controller;

use Candice\Onboarding\Application\SubmitEnrollment\SubmitEnrollmentService;
use Candice\Onboarding\Infrastructure\Symfony\DTO\SubmitEnrollmentRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class EnrollmentController extends AbstractController
{
    public function submit(
        #[MapRequestPayload] SubmitEnrollmentRequest $request,
        SubmitEnrollmentService $submitEnrollmentService
    ): Response {
        $response = $submitEnrollmentService->execute($request);

        return $this->json($response, Response::HTTP_CREATED);
    }
}
