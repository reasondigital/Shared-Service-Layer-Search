<?php

namespace App\Http\Controllers\Elastic;

use App\Constants\Data;
use App\Http\Controllers\BaseLocationController;
use App\Http\Response\ApiResponseBuilder;
use App\Models\Location;
use App\Pagination\DataNormalise;
use ElasticScoutDriverPlus\Builders\BoolQueryBuilder;
use ElasticScoutDriverPlus\QueryMatch;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

/**
 * Locations API controller for Elasticsearch.
 *
 * @package App\Http\Controllers\Elastic
 * @since 1.0.0
 */
class LocationController extends BaseLocationController
{
    /**
     * @since 1.0.0
     */
    const SEARCH_BY_OPTIONS = [
        'free-query',
        'coords',
        'postcode',
    ];

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
        $builder = $this->validateRequest($request, [
            'by' => ['required', 'string', Rule::in(self::SEARCH_BY_OPTIONS)],
            'query' => ['required'],
            'results' => ['sometimes', 'integer'],
            'page' => ['sometimes', 'integer'],
        ]);

        // Exit early if we have an error
        if ($builder->hasError()) {
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        }

        $by = $request->get('by');
        $query = $request->get('query');
        $perPage = $request->get('results');
        if ($perPage === null) {
            $perPage = config('search.results_per_page.locations');
        }

        switch ($by) {
            case 'coords':
                $latLng = explode(',', $query);

                $locationQuery = (new BoolQueryBuilder)
                    ->must('match_all')
                    ->filter('geo_distance', [
                        'distance' => '20000mi',
                        'geo.coordinates' => [
                            'lat' => $latLng[0],
                            'lon' => $latLng[1],
                        ],
                    ])
                ;

                $paginator = Location::nestedSearch()
                    ->path('geo')
                    ->query($locationQuery)
                    ->paginate($perPage)
                ;
                break;
        }

        // Add "results per page" value to response pagination links
        if (!is_null($request->get('results'))) {
            $paginator->appends('results', $perPage);
        }

        $found = [];
        foreach ($paginator as $result) {
            /** @var QueryMatch $result */
            $found[] = $result->model()->toSearchableArray();
        }

        // Build successful response.
        $builder->setStatusCode(200);
        $builder->setData($found);
        if (!empty($found)) {
            $builder->addMeta('pagination', DataNormalise::fromIlluminatePaginator($paginator->toArray()));
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
