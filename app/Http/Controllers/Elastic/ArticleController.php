<?php

namespace App\Http\Controllers\Elastic;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponseBuilder;
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
     * @param  ApiResponseBuilder  $builder
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function get(Request $request, ApiResponseBuilder $builder): JsonResponse
    {
        // Validate the request first.
        $builder = $this->validateRequest($request, [
            'query' => 'required',
        ]);

        // If we don't have an error then do the search.
        if (!$builder->hasError()) {
            // Do the search via scout.
            $query = $request->get('query');
            // @todo - Page and results need to be passed too?
            $found = Article::search($query)->get()->toArray();

            // Build successful response.
            $builder->setStatusCode(200);
            $builder->setData($found);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
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
