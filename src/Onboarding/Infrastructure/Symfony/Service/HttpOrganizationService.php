<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Exception\InvalidOrganizationRegistrationNumberException;
use Candice\Onboarding\Domain\Service\OrganizationServiceInterface;
use Candice\Onboarding\Infrastructure\Symfony\DTO\OrganizationDTO;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpOrganizationService implements OrganizationServiceInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(
        HttpClientInterface $onboardingOrganizationHttpClient
    ) {
        $this->httpClient = $onboardingOrganizationHttpClient;
    }

    /**
     * @throws InvalidOrganizationRegistrationNumberException
     */
    public function validateRegistrationNumber(string $registrationNumber): void
    {
        $response = $this->httpClient->request('GET', sprintf("organizations/%s/validate", $registrationNumber));

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new RuntimeException('Unable to validate registration number');
        }

        $content = $response->toArray();

        if ($content['valid'] === false) {
            throw new InvalidOrganizationRegistrationNumberException($registrationNumber, $content['reason']);
        }
    }

    public function existsByRegistrationNumber(string $registrationNumber): bool
    {
        $response = $this->httpClient->request('GET', sprintf("organizations/%s", $registrationNumber));

        return $response->getStatusCode() === Response::HTTP_OK;
    }

    public function register(OrganizationDTO $organization): void
    {
        $response = $this->httpClient->request('POST', 'organizations', [
            'json' => [
                'registrationNumber' => $organization->getRegistrationNumber(),
                'name' => $organization->getName(),
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_CREATED) {
            throw new RuntimeException('Unable to register organization');
        }
    }
}
