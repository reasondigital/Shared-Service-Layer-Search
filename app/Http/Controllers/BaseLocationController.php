<?php

namespace App\Http\Controllers;

use App\Geo\Address;
use App\Http\Response\ApiResponseBuilder;
use App\Models\Location;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Base controller for any Location endpoint controllers.
 *
 * @package App\Http\Controllers
 * @since   1.0.0
 */
abstract class BaseLocationController extends SearchController
{
    /**
     * @since 1.0.0
     */
    const ERROR_MSG_NOT_FOUND = 'No location was found with the given ID';

    /**
     * Retrieve a specific instance of the resource.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  Request  $request
     * @param  int      $id
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function get(Request $request, int $id): JsonResponse
    {
        /** @var Location $location */
        $location = Location::find($id);
        $builder = app()->make(ApiResponseBuilder::class);

        if (is_null($location)) {
            $builder->setError(404, self::ERROR_CODE_NOT_FOUND, self::ERROR_MSG_NOT_FOUND);
        } else {
            $builder->setStatusCode(200);
            $builder->setData($location->toSearchableArray());
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  int  $id
     *
     * @return JsonResponse|Response
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(int $id)
    {
        /** @var Location|null $location */
        $location = Location::find($id);

        if (is_null($location)) {
            $builder = app()->make(ApiResponseBuilder::class);
            $builder->setError(404, self::ERROR_CODE_NOT_FOUND, self::ERROR_MSG_NOT_FOUND);
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        } else {
            $location->delete();
            return response()->noContent();
        }
    }

    /**
     * @param  Address  $address
     *
     * @return array
     * @since 1.0.0
     */
    protected function getCoords(Address $address): array
    {
        if ($address->empty()) {
            return [];
        } else {
            return [
                'lat' => $address->latitude,
                'lon' => $address->longitude,
            ];
        }
    }
}
