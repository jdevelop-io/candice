<?php

declare(strict_types=1);

namespace Candice\Organization\Infrastructure\Symfony\Controller;

use Candice\Organization\Application\GetOrganization\GetOrganizationRequest;
use Candice\Organization\Application\GetOrganization\GetOrganizationService;
use Candice\Organization\Application\RegistrationNumberValidation\RegistrationNumberValidationRequest;
use Candice\Organization\Application\RegistrationNumberValidation\RegistrationNumberValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class OrganizationController extends AbstractController
{
    public function registrationNumberValidation(
        RegistrationNumberValidationService $registrationNumberValidationService,
        string $registrationNumber
    ): Response {
        $response = $registrationNumberValidationService->execute(
            new RegistrationNumberValidationRequest($registrationNumber)
        );

        $payload = [
            'valid' => $response->isValid(),
        ];

        if (!$response->isValid() && $response->getReason() !== null) {
            $payload['reason'] = $response->getReason();
        }

        return $this->json($payload);
    }

    public function getOrganization(
        GetOrganizationService $getOrganizationService,
        string $registrationNumber
    ): Response {
        $response = $getOrganizationService->execute(new GetOrganizationRequest($registrationNumber));

        return $this->json([
            'registrationNumber' => $response->getRegistrationNumber(),
            'name' => $response->getName(),
        ]);
    }
}
