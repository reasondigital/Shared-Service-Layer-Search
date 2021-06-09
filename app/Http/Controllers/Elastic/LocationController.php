<?php

namespace App\Http\Controllers\Elastic;

use App\Constants\ApiAbilities;
use App\Constants\DataConstants;
use App\Exceptions\DataNormaliseException;
use App\Exceptions\IncorrectPermissionHttpException;
use App\Geo\Coding\Search;
use App\Http\Controllers\BaseLocationController;
use App\Models\Location;
use App\Models\Shape;
use App\Pagination\DataNormalise;
use ElasticScoutDriverPlus\Builders\BoolQueryBuilder;
use ElasticScoutDriverPlus\Builders\NestedQueryBuilder;
use ElasticScoutDriverPlus\QueryMatch;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Polyline;

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
     * @throws BindingResolutionException|IncorrectPermissionHttpException
     * @since 1.0.0
     */
    public function store(Request $request): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);

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
            'sensitive' => ['sometimes', 'boolean'],
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
     * @throws BindingResolutionException|IncorrectPermissionHttpException|DataNormaliseException
     * @since 1.0.0
     */
    public function search(Request $request): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::READ_PUBLIC);

        $builder = $this->validateRequest($request, [
            // Search by; required
            'by' => ['required', 'string', Rule::in(self::SEARCH_BY_OPTIONS)],
            'query' => ['required', 'string'],

            // Constrain search by; optional, only one allowed
            'boundingShapeId' => ['sometimes', 'integer'],
            'boundingShape' => ['sometimes', 'string'],
            'distance' => ['sometimes', 'integer'],

            // Manipulate response by; optional
            'results' => ['sometimes', 'integer'],
            'page' => ['sometimes', 'integer'],
        ]);

        // Collect any constraints provided by the request
        $constraintsProvided = [];
        foreach (['boundingShape', 'boundingShapeId', 'distance'] as $constraintKey) {
            $constraintValue = $request->get($constraintKey);

            if (!is_null($constraintValue)) {
                $constraintsProvided[] = [
                    'key' => $constraintKey,
                    'value' => $constraintValue,
                ];
            }
        }

        if (count($constraintsProvided) === 1) {
            $constraint = $constraintsProvided[0];
        } elseif (count($constraintsProvided) > 1) {
            // Add "too many constraints" error to field errors list
            $fieldErrors = $builder->getMeta('field_errors', []);
            foreach ($constraintsProvided as $constraintProvided) {
                $fieldErrors[$constraintProvided['key']] = "Conflicting parameters provided. Of 'boundingShape', 'boundingShapeId' and 'distance', only one of these can be accepted by this endpoint in a single request.";
            }

            $builder->updateMeta('field_errors', $fieldErrors);
        }

        if (empty($constraint)) {
            $constraint = [
                'key' => null,
                'value' => null,
            ];
        }

        // Check that the given shape ID is valid (if appropriate)
        if ($constraint['key'] === 'boundingShapeId' && Shape::find($constraint['value']) === null) {
            $builder->setError(400, 'invalid_shape_id', 'The given Shape ID is not known by the service');
        }

        if ($constraint['key'] === 'boundingShape') {
            $shapeCoords = Polyline::pair(Polyline::decode($constraint['value']));
            $geoShapeClosed = $shapeCoords[array_key_first($shapeCoords)] === $shapeCoords[array_key_last($shapeCoords)];

            if (!$geoShapeClosed) {
                $fieldErrors = $builder->getMeta('field_errors', []);
                $fieldErrors[$constraint['key']] = "The first and last points of the 'boundingShape' parameter must be the same.";
                $builder->updateMeta('field_errors', $fieldErrors);
            } else {
                $constraint['value'] = $shapeCoords;
            }
        }

        // Set error on the response if field errors have been detected
        if (!$builder->hasError() && $builder->hasMeta('field_errors')) {
            $builder->setError(
                400,
                self::ERROR_CODE_VALIDATION,
                self::ERROR_MSG_VALIDATION
            );
        }

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

        $coords = [];
        switch ($by) {
            case 'free-query':
                $geoSearch = app()->make(Search::class);
                $address = $geoSearch->find($query);
                $coords = $this->getCoords($address);
                break;

            case 'postcode':
                // todo Maybe add this as a custom validation rule
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
                        self::ERROR_MSG_VALIDATION
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

        // Location filter (constraint) query
        $locQuery = new BoolQueryBuilder;

        // Apply constraint
        switch ($constraint['key']) {
            case 'boundingShape':
                // Switch lat/lon to lon/lat, as required by Elastic
                $constraint['value'] = array_map(function ($point) {
                    return [$point[1], $point[0]];
                }, $constraint['value']);

                $locQuery->filter('geo_shape', [
                    'geo.coordinates' => [
                        'shape' => [
                            'type' => 'polygon',
                            'coordinates' => [$constraint['value']],
                        ],
                    ],
                ]);
                break;

            case 'boundingShapeId':
                $locQuery->filter('geo_shape', [
                    'geo.coordinates' => [
                        'indexed_shape' => [
                            'index' => config('elastic.migrations.index_name_prefix') . 'shapes',
                            'id' => (int) $constraint['value'],
                        ],
                    ],
                ]);
                break;

            default:
            case 'distance':
                if (is_null($constraint['value'])) {
                    $constraint['value'] = config('search.radius');
                }

                $locQuery->filter('geo_distance', [
                    'distance' => "{$constraint['value']}mi",
                    'geo.coordinates' => $coords,
                ]);
                break;
        }

        // Full search query
        $search = Location::boolSearch()
            ->sortRaw([
                [
                    '_geo_distance' => [
                        'geo.coordinates' => $coords,
                        'order' => 'asc',
                        'unit' => 'mi',
                        'nested' => ['path' => 'geo'],
                    ],
                ],
            ])
            ->must(
                (new NestedQueryBuilder)
                    ->path('geo')
                    ->query($locQuery)
            );

        // Filter out sensitive content if appropriate
        if (!$request->user()->tokenCan(ApiAbilities::READ_SENSITIVE)) {
            $search->filter('term', ['sensitive' => false]);
        }

        // Execute query and paginate results
        $paginator = $search->paginate($perPage)->withQueryString();

        $found = [];
        foreach ($paginator as $result) {
            /** @var QueryMatch $result */
            $resultData = $result->model()->toResponseArray();
            $resultData['distance'] = round($result->raw()['sort'][0], 2);

            $found[] = $resultData;
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
     * @throws BindingResolutionException|IncorrectPermissionHttpException
     * @since 1.0.0
     */
    public function update(Request $request, Location $location): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);

        // Validate the request first
        // todo Move rules to somewhere central as they will be the same regardless of search provider
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
            'sensitive' => ['sometimes', 'boolean'],
        ]);

        // If we don't have an error then update the location
        if (!$builder->hasError()) {
            $location->fill($request->all());

            if ($location->isDirty()) {
                $location->save();
            }

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
