<?php
declare(strict_types=1);

namespace App\Security;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class MemberstackAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);

        $token = $data['token'] ?? '';

        $keys = file_get_contents('http://member-jwt.s3-website-us-east-1.amazonaws.com');
        $keySet = JWK::parseKeySet(json_decode($keys, true));

        $decodedToken = JWT::decode($token, $keySet);

        return new SelfValidatingPassport(new UserBadge($decodedToken->id));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new JsonResponse(['appUrl' => '/app']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response(null, Response::HTTP_UNAUTHORIZED);
    }
}