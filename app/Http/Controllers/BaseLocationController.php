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
    const MODEL_CLASS = Location::class;

    /**
     * Retrieve a specific instance of the resource.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  Request   $request
     * @param  Location  $location
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function get(Request $request, Location $location): JsonResponse
    {
        $builder = app()->make(ApiResponseBuilder::class);
        $builder->setStatusCode(200);
        $builder->setData($location->toSearchableArray());

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Location $location
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    abstract public function update(Request $request, Location $location): JsonResponse;

    /**
     * Remove the specified resource from storage.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  Location $location
     *
     * @return Response JSON response is sent by middleware when the resource
     *                  can't be found.
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(Location $location): Response
    {
        $location->delete();
        return response()->noContent();
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
