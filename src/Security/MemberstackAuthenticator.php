<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class MemberstackAuthenticator implements AuthenticatorInterface
{
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-Memberstack-Token');
    }

    public function authenticate(Request $request): Passport {
        $apiToken = $request->headers->get('X-Memberstack-Token');
        return new Passport(
            new UserBadge($apiToken, function($apiToken) {
                // Qui puoi recuperare e restituire l'utente usando l'API di Memberstack
            }),
            new CustomCredentials(function($credentials, UserInterface $user) {
                // Qui puoi implementare una verifica personalizzata delle credenziali, se necessario
                return true;
            }, $apiToken)
        );
    }


    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        $user = $passport->getUser();

        return new UsernamePasswordToken($user, $firewallName, $user->getRoles());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/app');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }
}