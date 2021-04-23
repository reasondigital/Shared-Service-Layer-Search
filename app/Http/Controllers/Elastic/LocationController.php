<?php

namespace App\Http\Controllers\Elastic;

use App\Constants\DataConstants;
use App\Geo\Coding\Search;
use App\Http\Controllers\BaseLocationController;
use App\Models\Location;
use App\Pagination\DataNormalise;
use ElasticScoutDriverPlus\Builders\BoolQueryBuilder;
use ElasticScoutDriverPlus\QueryMatch;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Locations API controller for Elasticsearch.
 *
 * @package App\Http\Controllers\Elastic
 * @since   1.0.0
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
            'postalCode' => ['required', 'string', 'regex:'.DataConstants::POSTAL_CODE_REGEX_UK],
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
            $builder->setData($location->toResponseArray());

            $builder->addLink('get_location', [
                'type' => 'GET',
                'href' => route('locations.get', ['id' => $location->id]),
            ]);
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
            'query' => ['required', 'string'],
            'distance' => ['sometimes', 'integer'],
            'results' => ['sometimes', 'integer'],
            'page' => ['sometimes', 'integer'],
        ]);

        // Exit early if we have an error
        if ($builder->hasError()) {
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        }

        $by = $request->get('by');
        $query = $request->get('query');

        $distance = $request->get('distance');
        if (is_null($distance)) {
            $distance = config('search.radius');
        }

        $perPage = $request->get('results');
        if ($perPage === null) {
            $perPage = config('search.results_per_page.locations');
        }

        $coords = [];
        switch ($by) {
            case 'free-query':
                $geoSearch = app()->make(Search::class);
                $address = $geoSearch->find($query);
                $coords = $this->getCoords($address);
                break;

            case 'postcode':
                $geoSearch = app()->make(Search::class);
                $address = $geoSearch->findByPostalCode($query);
                $coords = $this->getCoords($address);
                break;

            case 'coords':
                $latLng = explode(',', $query);
                if (count($latLng) === 2) {
                    $coords = [
                        'lat' => $latLng[0],
                        'lon' => $latLng[1],
                    ];
                } else {
                    $builder->setError(
                        400,
                        self::ERROR_CODE_VALIDATION,
                        'The data provided was invalid. The request has not been fulfilled.'
                    );
                    $builder->addMeta('field_errors', [
                        'query' => "Coordinates provided are invalid. Format should be '{lat},{lon}'",
                    ]);
                    return response()->json($builder->getResponseData(), $builder->getStatusCode());
                }
                break;
        }

        $builder->setStatusCode(200);
        if (empty($coords)) {
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        }

        // Coordinates item is nested so build the deepest query first
        $locationQuery = (new BoolQueryBuilder)
            ->must('match_all')
            ->filter('geo_distance', [
                'distance' => "{$distance}mi",
                'geo.coordinates' => $coords,
            ]);

        // Pass the deeper query to the top level query and run
        $paginator = Location::nestedSearch()
            ->path('geo')
            ->query($locationQuery)
            ->paginate($perPage)
            ->withQueryString();

        $found = [];
        foreach ($paginator as $result) {
            /** @var QueryMatch $result */
            $found[] = $result->model()->toResponseArray();
        }

        // Build response data
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
     * @param  Request   $request
     * @param  Location  $location
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function update(Request $request, Location $location): JsonResponse
    {
        // Validate the request first
        $builder = $this->validateRequest($request, [
            'id' => ['required', 'integer'],
            'streetAddress' => ['sometimes', 'string'],
            'addressRegion' => ['sometimes', 'string'],
            'addressLocality' => ['sometimes', 'string'],
            'addressCountry' => ['sometimes', 'string'],
            'postalCode' => ['sometimes', 'string', 'regex:'.DataConstants::POSTAL_CODE_REGEX_UK],
            'latitude' => ['sometimes', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['sometimes', 'numeric', 'min:-180', 'max:180'],
            'description' => ['sometimes', 'string'],
            'photoUrl' => ['sometimes', 'url'],
            'photoDescription' => ['sometimes', 'string'],
        ]);

        // If we don't have an error then update the location
        if (!$builder->hasError()) {
            $location->update($request->all());
            $location->save();
            $builder->setStatusCode(200);
            $builder->setData($location->toResponseArray());

            $builder->addLink('get_location', [
                'type' => 'GET',
                'href' => route('locations.get', ['id' => $location->id]),
            ]);
            $builder->addLink('delete_location', [
                'type' => 'DELETE',
                'href' => route('locations.delete', ['id' => $location->id]),
            ]);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }
}
