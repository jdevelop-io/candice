<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Controller;

use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailRequest;
use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    public function __construct(private readonly GetUserByEmailService $getUserByEmailService)
    {
    }

    public function getUserByEmail(string $email): JsonResponse
    {
        $response = $this->getUserByEmailService->execute(new GetUserByEmailRequest($email));

        return new JsonResponse([
            'id' => $response->getId(),
            'email' => $response->getEmail(),
        ]);
    }
}
