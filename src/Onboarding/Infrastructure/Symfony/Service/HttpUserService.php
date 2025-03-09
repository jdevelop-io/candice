<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Service\UserServiceInterface;
use Candice\Onboarding\Infrastructure\Symfony\DTO\UserDTO;
use RuntimeException;
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

    public function register(UserDTO $user): void
    {
        $response = $this->httpClient->request('POST', 'users', [
            'json' => [
                'email' => $user->getEmail(),
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_CREATED) {
            throw new RuntimeException('Unable to register user');
        }
    }
}
