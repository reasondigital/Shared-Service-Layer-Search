<?php

namespace App\Http\Middleware;

use App\Constants\ErrorMessages;
use App\Http\Response\ApiResponseBuilder;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

/**
 * Passes correctly formatted data and message content back to the client when
 * an ID'd resource can't be found.
 *
 * todo This can be removed, but routes will need updating (e.g. '/{id}' to '/{article}')
 *  and feature tests will need to be checked and amended.
 *
 * @package App\Http\Middleware
 * @since 1.0.0
 */
class HandleApi404AsJson
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route()->parameter('id');
        if (is_null($id)) {
            return $next($request);
        }

        $modelClass = $request->route()->getController()::MODEL_CLASS;
        $record = $modelClass::find($id);

        if (is_null($record)) {
            abort(404, ErrorMessages::MSG_NOT_FOUND);
        }

        // Controller methods are expecting a resolved resource
        $request->route()->setParameter('id', $record);

        return $next($request);
    }
}
