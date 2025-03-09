<?php

declare(strict_types=1);

namespace Candice\IdentityAndAccess\Infrastructure\Symfony\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class PrivateApiAuthenticator extends AbstractAuthenticator
{
    private const string API_TOKEN_HEADER = 'X-API-TOKEN';

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::API_TOKEN_HEADER);
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get(self::API_TOKEN_HEADER);

        if ($token === null) {
            throw new CustomUserMessageAuthenticationException('API token is missing');
        }

        return new SelfValidatingPassport(new UserBadge($token));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new UnauthorizedHttpException('X-API-TOKEN', $exception->getMessage());
    }
}
