<?php

namespace App\Exceptions;

use App\Http\Response\ApiResponseBuilder;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;

/**
 * Thrown when an access token does not have the ability required to perform
 * the action that it is attempting.
 *
 * @package App\Exceptions
 * @since   1.0.0
 */
class IncorrectPermissionException extends Exception
{
    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function render(): JsonResponse
    {
        $builder = app()->make(ApiResponseBuilder::class);
        $builder->setError(403, 'forbidden', $this->getMessage());
        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }
}
