<?php

namespace App\Http\Controllers;

use App\Exceptions\IncorrectPermissionHttpException;
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
 * @since   1.0.0
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
    const ERROR_MSG_VALIDATION = 'The data provided was invalid. The request has not been fulfilled.';

    /**
     * Confirm that the given request user has the given ability.
     *
     * @param  Request  $request  A Laravel request object.
     * @param  string   $ability  The ability to check the token against.
     * @param  string   $message  The error message to provided in the response.
     *
     * @throws IncorrectPermissionHttpException
     * @since 1.0.0
     */
    protected function validatePermission(Request $request, string $ability, string $message = '')
    {
        if (!$request->user()->tokenCan($ability)) {
            if (empty($message)) {
                $message = 'You do not have the permission required to take this action';
            }

            throw new IncorrectPermissionHttpException(403, $message);
        }
    }

    /**
     * Run the given validation rules against the given request's data.
     *
     * @param  Request  $request
     * @param  array    $rules
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
                self::ERROR_MSG_VALIDATION
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
