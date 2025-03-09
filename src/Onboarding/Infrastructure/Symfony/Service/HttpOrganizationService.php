<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Exception\InvalidOrganizationRegistrationNumberException;
use Candice\Onboarding\Domain\Service\OrganizationServiceInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpOrganizationService implements OrganizationServiceInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $baseUrl,
        private string $token,
    ) {
    }

    /**
     * @throws InvalidOrganizationRegistrationNumberException
     * @throws TransportExceptionInterface
     */
    public function validateRegistrationNumber(string $registrationNumber): void
    {
        $url = sprintf("%s/organizations/%s/validate", $this->baseUrl, $registrationNumber);

        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'X-API-TOKEN' => $this->token,
            ],
        ]);

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
        $url = sprintf("%s/organizations/%s", $this->baseUrl, $registrationNumber);

        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'X-API-TOKEN' => $this->token,
            ],
        ]);
        
        return $response->getStatusCode() === Response::HTTP_OK;
    }
}
