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
use Illuminate\Http\Response;

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
     * @since 1.0.0
     */
    public function store(Request $request): JsonResponse
    {
        $this->validatePermission($request, ApiAbilities::WRITE);

        $builder = $this->validateRequest($request, [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'coordinates' => ['required', 'array', 'min:4', new GeoShapeClosed],
            'coordinates.*.lat' => ['required', 'numeric', 'min:-90', 'max:90'],
            'coordinates.*.lon' => ['required', 'numeric', 'min:-180', 'max:180'],
        ]);

        if (!$builder->hasError()) {
            $shape = new Shape($request->all());

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
        }

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

        $builder = $this->validateRequest($request, [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'coordinates' => ['sometimes', 'array', 'min:4', new GeoShapeClosed],
            'coordinates.*.lat' => ['sometimes', 'numeric', 'min:-90', 'max:90'],
            'coordinates.*.lon' => ['sometimes', 'numeric', 'min:-180', 'max:180'],
        ]);

        if (!$builder->hasError()) {
            $shape->fill($request->all());

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
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Shape  $shape
     *
     * @return Response
     * @throws Exception
     * @since 1.0.0
     */
    public function destroy(Shape $shape)
    {
        $this->validatePermission(request(), ApiAbilities::WRITE);

        $shape->delete();
        return response()->noContent();
    }

    /**
     * Ensure that a coordination point array's values are float values.
     *
     * @param  array  $point
     *
     * @return float[]
     * @since 1.0.0
     */
    private function normalisePointCoordsAsFloats(array $point)
    {
        return [
            'lat' => (float) $point['lat'] ?? 0.0,
            'lon' => (float) $point['lon'] ?? 0.0,
        ];
    }
}
