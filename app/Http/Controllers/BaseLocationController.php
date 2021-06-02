<?php

namespace App\Http\Controllers;

use App\Constants\ApiAbilities;
use App\Constants\ErrorMessages;
use App\Exceptions\IncorrectPermissionHttpException;
use App\Geo\Address;
use App\Http\Response\ApiResponseBuilder;
use App\Models\Location;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @throws BindingResolutionException|IncorrectPermissionHttpException|NotFoundHttpException
     * @since 1.0.0
     */
    public function get(Request $request, Location $location): JsonResponse
    {
        if ($location->sensitive) {
            if (!$request->user()->tokenCan(ApiAbilities::READ_SENSITIVE)) {
                abort(404, ErrorMessages::MSG_NOT_FOUND);
            }
        }

        $this->validatePermission($request, ApiAbilities::READ_PUBLIC);

        $builder = app()->make(ApiResponseBuilder::class);
        $builder->setStatusCode(200);
        $builder->setData($location->toResponseArray());

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
     * @param  Location            $location
     * @param  ApiResponseBuilder  $builder
     *
     * @return JsonResponse
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(Location $location, ApiResponseBuilder $builder): JsonResponse
    {
        $this->validatePermission(request(), ApiAbilities::WRITE);

        $location->delete();

        $builder->setStatusCode(200);
        return response()->json($builder->getResponseData(), $builder->getStatusCode());
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
