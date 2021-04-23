<?php

namespace App\Exceptions;

use App\Http\Response\ApiResponseBuilder;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Force unauthenticated errors to respond as JSON.
     *
     * @param  Request                  $request
     * @param  AuthenticationException  $exception
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse
    {
        $builder = app()->make(ApiResponseBuilder::class);
        $builder->setError(401, 'unauthorised', $exception->getMessage());
        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }
}
