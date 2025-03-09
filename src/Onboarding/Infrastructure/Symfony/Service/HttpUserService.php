<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpUserService implements UserServiceInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $onboardingUserHttpClient)
    {
        $this->httpClient = $onboardingUserHttpClient;
    }

    public function existsByEmail(string $email): bool
    {
        $response = $this->httpClient->request('GET', sprintf("users?email=%s", $email));

        return $response->getStatusCode() === Response::HTTP_OK;
    }
}
