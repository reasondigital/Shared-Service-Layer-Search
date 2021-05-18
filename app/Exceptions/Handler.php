<?php

namespace App\Exceptions;

use App\Http\Response\ApiResponseBuilder;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;
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

    /**
     * Format the default JSON response for exceptions thrown in this
     * application.
     *
     * @param  Request    $request
     * @param  Throwable  $e
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    protected function prepareJsonResponse($request, Throwable $e): JsonResponse
    {
        if ($this->isHttpException($e)) {
            $code = $e->getStatusCode();
            $errorCode = Str::snake((new ReflectionClass($e))->getShortName());
            $errorCode = str_replace('_http_exception', '', $errorCode);
            $headers = $e->getHeaders();
        } else {
            $code = 500;
            $errorCode = 'internal_error';
            $headers = [];
        }

        $builder = app()->make(ApiResponseBuilder::class);
        $builder->setError($code, $errorCode, $e->getMessage());

        if (config('app.debug') === true) {
            $builder->addMeta('exception', $this->convertExceptionToArray($e));
        }

        return response()->json(
            $builder->getResponseData(),
            $builder->getStatusCode(),
            $headers,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

    }
}
