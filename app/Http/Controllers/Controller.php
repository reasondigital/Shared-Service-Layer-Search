<?php

namespace App\Http\Controllers;

use App\Http\Response\ApiResponseBuilder;
use App\Http\Response\JsonApiResponseBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

/**
 * Application base controller.
 *
 * @package App\Http\Controllers
 * @since 1.0.0
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @since 1.0.0
     */
    const ERROR_CODE_VALIDATION = 'validation_error';

    /**
     * @since 1.0.0
     */
    const ERROR_CODE_NOT_FOUND = 'not_found';

    /**
     * @param  Request  $request
     * @param  array  $rules
     *
     * @return JsonApiResponseBuilder
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    protected function validateRequest(Request $request, array $rules): JsonApiResponseBuilder
    {
        // Get response builder
        $builder = app()->make(ApiResponseBuilder::class);

        /*
         * Get the validator instead of using `$this->validate()`.
         * This is so we can get the individual field errors and add them to
         * the response meta data alongside the response's main error message.
         */
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $builder->setError(
                400,
                self::ERROR_CODE_VALIDATION,
                'The data provided was invalid. The request has not been fulfilled.'
            );

            $fieldErrors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $fieldErrors[$field] = $messages[0];
            }
            $builder->addMeta('field_errors', $fieldErrors);
        }

        return $builder;
    }
}
