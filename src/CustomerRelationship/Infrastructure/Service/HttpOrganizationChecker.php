<?php

declare(strict_types=1);

namespace Candice\CustomerRelationship\Infrastructure\Service;

use Candice\CustomerRelationship\Domain\Service\OrganizationCheckerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpOrganizationChecker implements OrganizationCheckerInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $crmOrganizationHttpClient)
    {
        $this->httpClient = $crmOrganizationHttpClient;
    }

    public function existsById(string $id): bool
    {
        $response = $this->httpClient->request('GET', sprintf("organizations/%s", $id));

        return $response->getStatusCode() === Response::HTTP_OK;
    }
}
