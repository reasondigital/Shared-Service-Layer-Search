<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * A base controller class to be used as a contract for all controllers that
 * work with a search-indexed entity.
 *
 * @package App\Http\Controllers
 * @since   1.0.0
 */
abstract class SearchController extends Controller
{
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
     * Retrieve the specified resource.
     *
     * @param  Request  $request
     * @param  int      $id
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    abstract public function get(Request $request, int $id): JsonResponse;

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
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int      $id
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    abstract public function update(Request $request, int $id): JsonResponse;

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return JsonResponse|Response "204 No Content" response on success, a
     *                               standard JSON response otherwise.
     * @since 1.0.0
     */
    abstract public function destroy(int $id);
}
