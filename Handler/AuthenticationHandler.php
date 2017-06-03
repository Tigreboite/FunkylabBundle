<?php

namespace Tigreboite\FunkylabBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    protected $translator;

    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => $this->translator->trans('funkylab.login.badrequest')], 405);
        }

        return new JsonResponse(['message' => $this->translator->trans('funkylab.login.success')]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['message' => $this->translator->trans($exception->getMessage())], 401);
    }
}
