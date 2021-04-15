<?php

namespace App\Http\Controllers\Elastic;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Locations API controller for Elasticsearch.
 *
 * @package App\Http\Controllers\Elastic
 * @since 1.0.0
 */
class LocationController extends Controller
{
    const POSTCODE_REGEX = '/^(([A-Z][0-9]{1,2})|(([A-Z][A-HJ-Y][0-9]{1,2})|(([A-Z][0-9][A-Z])|([A-Z][A-HJ-Y][0-9]?[A-Z])))) [0-9][A-Z]{2}$/';

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
            'postalCode' => ['required', 'string', 'regex:' . self::POSTCODE_REGEX],
            'latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'description' => ['sometimes', 'string'],
            'photoUrl' => ['sometimes', 'url'],
            'photoDescription' => ['sometimes', 'string'],
        ]);

        // If we don't have an error then add the article
        if (!$builder->hasError()) {
            $article = new Location($request->all());
            $article->save();
            $builder->setStatusCode(200);
            $builder->setData($article->toArray());
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Retrieve the specified resource.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function get(Request $request): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Location  $location
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function update(Request $request, Location $location): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Location  $location
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function destroy(Location $location): JsonResponse
    {
        return response()->json([]);
    }
}
