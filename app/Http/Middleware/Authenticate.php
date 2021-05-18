<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * @package App\Http\Middleware
 * @since   1.0.0
 */
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
            'You are not authorised to consume this API'
        );
    }
}
