<?php

namespace App\Http\Controllers\Elastic;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponseBuilder;
use App\Http\Response\JsonApiResponseBuilder;
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
    const PUBLISHED_DATE_FORMAT = 'Y-m-d';

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
        // Validate the request first.
        $builder = $this->validateRequest($request, [
            'articleBody' => 'required|string',
            'abstract' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'required|string',
            'datePublished' => 'required|date_format:Y-m-d',
        ]);

        // If we don't have an error then add the article.
        if (!$builder->hasError()) {
            // @todo - Scout says this needs to be added to the db. Shouldn't
            // @todo - this go straight to the index?
            $article = new Article($request->all());
            $article->save();
            $builder->setStatusCode(200);
            $builder->setData([$article->toArray()]);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
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
            'results' => 'sometimes|integer',
            'page' => 'sometimes|integer'
        ]);

        // If we don't have an error then do the search.
        if (!$builder->hasError()) {
            // Do the search via scout.
            $query = $request->get('query');
            // @todo - Page and results need to be passed too?
            // @todo - Paginate with this: https://laravel.com/docs/8.x/scout#pagination
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
     * @todo - Will we allow a partial update or does the whole thing need to be
     * submitted?
     *
     * @return JsonResponse
     * @since 1.0.0
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        // Validate the request first.
        // @todo - Keeping the rules separate for now in case we need to split
        // @todo - between add and update
        $builder = $this->validateRequest($request, [
            'articleBody' => 'required|string',
            'abstract' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'required|string',
            'datePublished' => 'required|date_format:Y-m-d',
        ]);

        // If we don't have an error then add the article.
        if (!$builder->hasError()) {
            $article->update($request->all());
            $article->save();
            $builder->setStatusCode(200);
            $builder->setData([$article->toArray()]);
        }

        return response()->json($builder->getResponseData(), $builder->getStatusCode());
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
        $article->delete();
        $builder = new JsonApiResponseBuilder();
        $builder->setStatusCode(200);
        $builder->setData([]);
        return response()->json($builder->getResponseData(), $builder->getStatusCode());
    }
}
