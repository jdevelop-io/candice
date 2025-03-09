<?php

declare(strict_types=1);

namespace Candice\Organization\Infrastructure\Symfony\Controller;

use Candice\Organization\Application\Get\GetOrganizationRequest;
use Candice\Organization\Application\Get\GetOrganizationService;
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

        return $this->json([
            'valid' => $response->isValid(),
            'reason' => $response->getReason(),
        ]);
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
