<?php

namespace App\Http\Controllers\Elastic;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Articles API controller for Elasticsearch.
 *
 * @package App\Http\Controllers\Elastic
 * @since 1.0.0
 */
class ArticleController extends Controller
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
        return response()->json([]);
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
     * @param  Article  $article
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article  $article
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function destroy(Article $article): JsonResponse
    {
        return response()->json([]);
    }
}
