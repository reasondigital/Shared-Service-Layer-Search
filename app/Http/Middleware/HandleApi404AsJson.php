<?php

namespace App\Http\Middleware;

use App\Constants\ErrorConstants;
use App\Http\Response\ApiResponseBuilder;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

/**
 * Passes correctly formatted data back to the client when an ID'd resource
 * can't be found. Otherwise, Laravel would return an HTML document.
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
            $builder = app()->make(ApiResponseBuilder::class);
            $builder->setError(404, ErrorConstants::CODE_NOT_FOUND, ErrorConstants::MSG_NOT_FOUND);
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        }

        // Controller methods are expecting a resolved resource
        $request->route()->setParameter('id', $record);

        return $next($request);
    }
}
