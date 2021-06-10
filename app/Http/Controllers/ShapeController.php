<?php

namespace App\Http\Controllers;

use App\Constants\ApiAbilities;
use App\Http\Response\ApiResponseBuilder;
use App\Models\Shape;
use App\Rules\GeoShapeClosed;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Polyline;

/**
 * Shapes API controller for the application.
 *
 * @package App\Http\Controllers
 * @since   1.0.0
 */
class ShapeController extends Controller
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
        $this->validatePermission($request, ApiAbilities::WRITE);
        $builder = $this->validateShapeInsert($request);

        // Exit here if an error has been detected
        if ($builder->hasError()) {
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        }

        // 'polyline' param will be ignored as it's not in the $fillable property
        $shape = new Shape($request->all());

        // Update coords attribute if shape was provided as polyline
        $polyline = $request->input('polyline', '');
        if (!empty($polyline)) {
            $shape->coordinates = $this->polylineToCoordinates($polyline);
        }

        // Ensure values are floats
        $shape->coordinates = array_map([$this, 'normalisePointCoordsAsFloats'], $shape->coordinates);

        $shape->save();
        $builder->setStatusCode(201);
        $builder->setData($shape->toResponseArray());

        $builder->addLink('get_shape', [
            'type' => 'GET',
            'href' => route('shapes.get', ['shape' => $shape]),
        ]);
        $builder->addLink('update_shape', [
            'type' => 'PUT',
            'href' => route('shapes.put', ['shape' => $shape]),
        ]);
        $builder->addLink('delete_shape', [
            'type' => 'DELETE',
            'href' => route('shapes.delete', ['shape' => $shape]),
        ]);

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Retrieve a list of the resource.
     *
     * @param  Request             $request
     * @param  ApiResponseBuilder  $builder
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function list(Request $request, ApiResponseBuilder $builder): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);

        $shapes = [];
        foreach (Shape::all() as $shape) {
            /** @var Shape $shape */
            $shapes[] = $shape->toResponseArray();
        }

        $builder->setStatusCode(200);
        $builder->setData($shapes);

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Retrieve a specific instance of the resource.
     *
     * @param  Request  $request
     * @param  Shape    $shape
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function get(Request $request, Shape $shape): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);

        $builder = app()->make(ApiResponseBuilder::class);
        $builder->setStatusCode(200);
        $builder->setData($shape->toResponseArray());

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Shape    $shape
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    public function update(Request $request, Shape $shape): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);
        $builder = $this->validateShapeInsert($request);

        // Exit here if an error has been detected
        if ($builder->hasError()) {
            return response()->json($builder->getResponseData(), $builder->getStatusCode());
        }

        $shape->fill($request->all());

        // Update coords attribute if shape was provided as polyline
        $polyline = $request->input('polyline', '');
        if (!empty($polyline)) {
            $shape->coordinates = $this->polylineToCoordinates($polyline);
        }

        if ($shape->isDirty()) {
            if ($shape->isDirty('coordinates')) {
                // Ensure values are floats
                $shape->coordinates = array_map([$this, 'normalisePointCoordsAsFloats'], $shape->coordinates);
            }

            $shape->save();
        }

        $builder->setStatusCode(200);
        $builder->setData($shape->toResponseArray());

        $builder->addLink('get_shape', [
            'type' => 'GET',
            'href' => route('shapes.get', ['shape' => $shape]),
        ]);
        $builder->addLink('delete_shape', [
            'type' => 'DELETE',
            'href' => route('shapes.delete', ['shape' => $shape]),
        ]);

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Shape               $shape
     * @param  ApiResponseBuilder  $builder
     *
     * @return JsonResponse
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(Shape $shape, ApiResponseBuilder $builder): JsonResponse
    {
        $this->validatePermission(request(), ApiAbilities::WRITE);

        $shape->delete();

        $builder->setStatusCode(200);
        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * @param  Request  $request
     *
     * @return ApiResponseBuilder
     * @throws BindingResolutionException
     * @since 1.0.0
     */
    private function validateShapeInsert(Request $request): ApiResponseBuilder
    {
        $builder = $this->validateRequest($request, [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'polyline' => ['required_without:coordinates', 'string'],
            'coordinates' => ['required_without:polyline', 'array', 'min:4', new GeoShapeClosed],
            'coordinates.*.lat' => ['required', 'numeric', 'min:-90', 'max:90'],
            'coordinates.*.lon' => ['required', 'numeric', 'min:-180', 'max:180'],
        ]);

        if ($builder->hasError()) {
            return $builder;
        }

        $polyline = $request->input('polyline', '');
        $coordinates = $request->input('coordinates', []);

        if (!empty($polyline) && !empty($coordinates)) {
            $fieldErrors = $builder->getMeta('field_errors', []);

            $conflictErrorMsg = "Conflicting parameters provided. Of 'polyline' and 'coordinates', only one of these can be accepted by this endpoint in a single request.";
            $fieldErrors['polyline'] = $conflictErrorMsg;
            $fieldErrors['coordinates'] = $conflictErrorMsg;

            $builder->updateMeta('field_errors', $fieldErrors);
        }

        if (!empty($polyline)) {
            $pCoordinates = Polyline::pair(Polyline::decode($polyline));

            if (!$this->validatePolylineCoords($pCoordinates)) {
                $fieldErrors = $builder->getMeta('field_errors', []);
                $fieldErrors['polyline'] = "The first and last points of the 'polyline' parameter coordinates must be the same.";
                $builder->updateMeta('field_errors', $fieldErrors);
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

        return $builder;
    }

    /**
     * @param  array  $pCoordinates
     *
     * @return bool
     * @since 1.0.0
     */
    private function validatePolylineCoords(array $pCoordinates): bool
    {
        return $pCoordinates[array_key_first($pCoordinates)] === $pCoordinates[array_key_last($pCoordinates)];
    }

    /**
     * @param  string  $polyline
     *
     * @return array
     * @since 1.0.0
     */
    private function polylineToCoordinates(string $polyline): array
    {
        $coordinates = [];

        if (empty($polyline)) {
            return $coordinates;
        }

        $pCoordinates = Polyline::pair(Polyline::decode($polyline));
        foreach ($pCoordinates as $point) {
            $coordinates[] = [
                'lat' => $point[0],
                'lon' => $point[1],
            ];
        }

        return $coordinates;
    }

    /**
     * Ensure that a coordination point array's values are float values.
     *
     * @param  array  $point
     *
     * @return float[]
     * @since 1.0.0
     */
    private function normalisePointCoordsAsFloats(array $point): array
    {
        return [
            'lat' => (float) $point['lat'] ?? 0.0,
            'lon' => (float) $point['lon'] ?? 0.0,
        ];
    }
}
