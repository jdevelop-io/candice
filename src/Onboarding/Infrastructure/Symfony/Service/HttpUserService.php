<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpUserService implements UserServiceInterface
{
    public function __construct(private HttpClientInterface $httpClient, private string $baseUrl, private string $token)
    {
    }

    public function existsByEmail(string $email): bool
    {
        $url = sprintf("%s/users?email=%s", $this->baseUrl, $email);

        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'X-API-TOKEN' => $this->token,
            ],
        ]);

        return $response->getStatusCode() === Response::HTTP_OK;
    }
}
