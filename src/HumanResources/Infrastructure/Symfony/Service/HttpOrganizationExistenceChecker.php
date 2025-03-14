<?php

declare(strict_types=1);

namespace Candice\HumanResources\Infrastructure\Symfony\Service;

use Candice\HumanResources\Domain\Service\OrganizationExistenceCheckerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpOrganizationExistenceChecker implements OrganizationExistenceCheckerInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $hrOrganizationHttpClient)
    {
        $this->httpClient = $hrOrganizationHttpClient;
    }

    public function existsById(string $id): bool
    {
        $response = $this->httpClient->request('GET', sprintf('organizations/%s', $id));

        return $response->getStatusCode() === Response::HTTP_OK;
    }
}
