<?php

namespace App\Http\Controllers;

use App\Http\Response\JsonApiResponseBuilder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const VALIDATION_ERROR_CODE = 'validation_error';

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $rules
     *
     * @return \App\Http\Response\JsonApiResponseBuilder
     */
    protected function validateRequest(Request $request, array $rules): JsonApiResponseBuilder {
        $builder = new JsonApiResponseBuilder();

        try {
            $this->validate($request, $rules);
        } catch (\Exception $e) {
            // @todo - Does this need to loop over validation errors?
            $builder->setError(
                400,
                self::VALIDATION_ERROR_CODE,
                $e->getMessage()
            );
        }

        return $builder;
    }
}
