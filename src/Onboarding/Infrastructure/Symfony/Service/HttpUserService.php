<?php

declare(strict_types=1);

namespace Candice\Onboarding\Infrastructure\Symfony\Service;

use Candice\Onboarding\Domain\Service\UserServiceInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpUserService implements UserServiceInterface
{
    public function __construct(private HttpClientInterface $httpClient, private string $baseUrl, private string $token)
    {
    }

    public function existsByEmail(string $email): bool
    {
        return false;
    }
}
