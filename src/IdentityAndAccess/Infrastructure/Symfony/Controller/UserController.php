<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Controller;

use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailRequest;
use Candice\IdentityAndAccess\Application\GetUserByEmail\GetUserByEmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends AbstractController
{
    public function __construct(private readonly GetUserByEmailService $getUserByEmailService)
    {
    }

    public function get(#[MapQueryParameter] ?string $email): Response
    {
        if ($email !== null) {
            return $this->getByEmail($email);
        }

        throw new BadRequestHttpException();
    }

    private function getByEmail(string $email): JsonResponse
    {
        $response = $this->getUserByEmailService->execute(new GetUserByEmailRequest($email));

        return new JsonResponse([
            'id' => $response->getId(),
            'email' => $response->getEmail(),
        ]);
    }
}
