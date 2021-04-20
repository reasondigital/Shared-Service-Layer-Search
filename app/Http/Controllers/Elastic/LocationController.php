<?php

namespace App\Http\Controllers\Elastic;

use App\Constants\Data;
use App\Http\Controllers\BaseLocationController;
use App\Http\Response\ApiResponseBuilder;
use App\Models\Location;
use App\Pagination\LengthAwarePaginator;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Locations API controller for Elasticsearch.
 *
 * @package App\Http\Controllers\Elastic
 * @since 1.0.0
 */
class LocationController extends BaseLocationController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the request first
        $builder = $this->validateRequest($request, [
            'streetAddress' => ['required', 'string'],
            'addressRegion' => ['sometimes', 'string'],
            'addressLocality' => ['sometimes', 'string'],
            'addressCountry' => ['required', 'string'],
            'postalCode' => ['required', 'string', 'regex:'.Data::POSTAL_CODE_REGEX_UK],
            'latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'description' => ['sometimes', 'string'],
            'photoUrl' => ['sometimes', 'url'],
            'photoDescription' => ['sometimes', 'string'],
        ]);

        // If we don't have an error then add the location
        if (!$builder->hasError()) {
            $location = new Location($request->all());
            $location->save();
            $builder->setStatusCode(201);
            $builder->setData($location->toSearchableArray());

            /*$builder->addLink('get_location', [
                'type' => 'GET',
                'href' => route('locations.get', ['id' => $location->id]),
            ]);*/
            $builder->addLink('update_location', [
                'type' => 'PUT',
                'href' => route('locations.put', ['id' => $location->id]),
            ]);
            $builder->addLink('delete_location', [
                'type' => 'DELETE',
                'href' => route('locations.delete', ['id' => $location->id]),
            ]);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Perform a search against the resource.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function search(Request $request): JsonResponse
    {
        // Will throw an exception where validation fails
        $builder = $this->validateRequest($request, [
            'query' => ['required'],
            'results' => ['sometimes', 'integer'],
            'page' => ['sometimes', 'integer'],
        ]);

        if (!$builder->hasError()) {
            $query = $request->get('query');
            $perPage = $request->get('results');

            /** @var LengthAwarePaginator $paginator */
            $paginator = Location::search($query)->paginate($perPage);

            if (!is_null($perPage)) {
                $paginator->appends('results', $perPage);
            }

            $found = [];
            foreach ($paginator as $result) {
                $found[] = $result->toSearchableArray();
            }

            // Build successful response.
            $builder->setStatusCode(200);
            $builder->setData($found);
            $builder->addMeta('pagination', $paginator->toArray());
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     *
     * todo Implement feature tests for this endpoint.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function update(Request $request, int $id): JsonResponse
    {
        /** @var Location|null $location */
        $location = Location::find($id);

        if (is_null($location)) {
            // Get response builder
            $builder = app()->make(ApiResponseBuilder::class);

            // Set and send error
            $builder->setError(404, self::ERROR_CODE_NOT_FOUND, self::ERROR_MSG_NOT_FOUND);
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        }

        // Validate the request first
        $builder = $this->validateRequest($request, [
            'id' => ['required', 'integer'],
            'streetAddress' => ['sometimes', 'string'],
            'addressRegion' => ['sometimes', 'string'],
            'addressLocality' => ['sometimes', 'string'],
            'addressCountry' => ['sometimes', 'string'],
            'postalCode' => ['sometimes', 'string', 'regex:'.Data::POSTAL_CODE_REGEX_UK],
            'latitude' => ['sometimes', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['sometimes', 'numeric', 'min:-180', 'max:180'],
            'description' => ['sometimes', 'string'],
            'photoUrl' => ['sometimes', 'url'],
            'photoDescription' => ['sometimes', 'string'],
        ]);

        // If we don't have an error then add the location
        if (!$builder->hasError()) {
            $location->update($request->all());
            $location->save();
            $builder->setStatusCode(200);
            $builder->setData($location->toSearchableArray());

            /*$builder->addLink('get_location', [
                'type' => 'GET',
                'href' => route('locations.get', ['id' => $location->id]),
            ]);*/
            $builder->addLink('delete_location', [
                'type' => 'DELETE',
                'href' => route('locations.delete', ['id' => $location->id]),
            ]);
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
}
