<?php

namespace App\Http\Controllers;

use App\Exceptions\IncorrectPermissionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * A low level controller class to be used as a base for all search-related
 * controllers.
 *
 * @package App\Http\Controllers
 * @since   1.0.0
 */
abstract class SearchController extends Controller
{
    /**
     * SearchController constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('api.handle404')->only(['get', 'update', 'destroy']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    abstract public function store(Request $request): JsonResponse;

    /**
     * Perform a search against the resource.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    abstract public function search(Request $request): JsonResponse;

    /**
     * @param  Request  $request A Laravel request object.
     * @param  string   $ability The ability to check the token against.
     * @param  string   $message The error message to provided in the response.
     *
     * @throws IncorrectPermissionException
     * @since 1.0.0
     */
    protected function validatePermission(Request $request, string $ability, string $message = '')
    {
        if (!$request->user()->tokenCan($ability)) {
            if (empty($message)) {
                $message = 'You do not have the permission required to take this action';
            }

            throw new IncorrectPermissionException($message);
        }
    }
}
