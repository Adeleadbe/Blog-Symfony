<?php

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class CustomAuthenticationEntryPoint implements AuthenticationEntryPointInterface 
{
    private string $loginRoute;

    public function __construct(string $loginRoute)
    {
        $this->loginRoute = $loginRoute;
    }

    public function start(Request $request, AuthenticationException $authenticationException = null)
    {
        return new RedirectResponse($this->loginRoute);
    }
}