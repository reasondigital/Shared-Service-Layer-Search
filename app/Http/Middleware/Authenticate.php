<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Overrides the default to send a message more appropriate for this API.
     *
     * @param  Request  $request
     * @param  array    $guards
     *
     * @throws AuthenticationException
     * @since 1.0.0
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'You are not authorised to consume this API',
            $guards,
            $this->redirectTo($request)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     *
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
