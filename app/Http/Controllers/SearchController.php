<?php

namespace App\Http\Controllers;

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
}
