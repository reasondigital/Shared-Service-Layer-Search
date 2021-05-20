<?php

namespace App\Http\Controllers;

use App\Constants\ApiAbilities;
use App\Models\Shape;
use App\Rules\GeoShapeClosed;
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function list()
    {
        //
    }

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
            'coordinates' => ['required', 'array', new GeoShapeClosed],
            'coordinates.*.lat' => ['required', 'numeric', 'min:-90', 'max:90'],
            'coordinates.*.lon' => ['required', 'numeric', 'min:-180', 'max:180'],
        ]);

        if (!$builder->hasError()) {
            $shape = new Shape($request->all());

            // Ensure values are floats
            $shape->coordinates = array_map(function ($point) {
                return [
                    'lat' => (float) $point['lat'],
                    'lon' => (float) $point['lon'],
                ];
            }, $shape->coordinates);

            $shape->save();
            $builder->setStatusCode(201);
            $builder->setData($shape->toResponseArray());

            $builder->addLink('get_shape', [
                'type' => 'GET',
                'href' => route('shapes.get', ['id' => $shape->id]),
            ]);
            $builder->addLink('update_shape', [
                'type' => 'PUT',
                'href' => route('shapes.put', ['id' => $shape->id]),
            ]);
            $builder->addLink('delete_shape', [
                'type' => 'DELETE',
                'href' => route('shapes.delete', ['id' => $shape->id]),
            ]);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Shape    $shape
     *
     * @return Response
     */
    public function update(Request $request, Shape $shape)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Shape  $shape
     *
     * @return Response
     */
    public function destroy(Shape $shape)
    {
        //
    }
}
